<?php

/**
 * This file is part of the Accard package.
 *
 * (c) University of Pennsylvania
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Accard\Bundle\HMTBBundle\Import;

use PDO;
use DateTime;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Accard\Bundle\SampleBundle\Import\SampleImporter;
use Accard\Bundle\ResourceBundle\Import\ImporterInterface;
use Accard\Bundle\CoreBundle\Provider\ImportPatientProvider;
use Accard\Component\Sample\Provider\SampleProvider;
use Accard\Component\Prototype\Provider\PrototypeProviderInterface;
use Accard\Component\Prototype\Model\PrototypeInterface;
use Doctrine\DBAL\Connection;

/**
 *  HMTB Specimens importer.
 *
 * @author Dylan Pierce <piercedy@upenn.edu>
 */
class SpecimensCollectionImporter extends SampleImporter
{
    /**
     * Patient provider.
     *
     * @var ImportPatientProvider
     */
    private $provider;

    /**
     * Prototype provider.
     * 
     * @var PrototypeProviderInterface
     */
    private $prototypeProvider;

    /**
     * PDS connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * Local connection.
     * 
     * @var Connection
     */
    private $defaultConnection;

    /**
     * Diagnosis codes.
     *
     * @var string
     */
    private $codes;

    /**
     * Constructor.
     *
     * @param ImportPatientProvider $provider
     * @param Connection $connection
     * @param Sample Provider
     * @param array $code
     * @param string|null $defaultStartDate
     */
    public function __construct(ImportPatientProvider $provider,
                                PrototypeProviderInterface $prototypeProvider,
                                Connection $connection,
                                Connection $defaultConnection,
                                array $codes)
    {
        $this->provider = $provider;
        $this->prototypeProvider = $prototypeProvider;
        $this->connection = $connection;
        $this->defaultConnection = $defaultConnection;
        $this->codes = $codes;
    }

    /**
     * Get diagnosis codes.
     * 
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }

    /**
     * {@inheritdoc}
     */
    public function run(OptionsResolverInterface $resolver, array $criteria)
    {
        $records = array();

        if ($criteria['first_id'] == $criteria['last_id']) {
            return $records;
        }

        $stmt = $this->connection->prepare($this->getSQL());
        $stmt->execute(array(
            'first_id' => $criteria['first_id'],
            'last_id' => $criteria['last_id'],
        ));

        $results = $stmt->fetchAll();
        $stmt->closeCursor();
        $prototype = $this->prototypeProvider->getPrototypeByName('hmtb-samples');
        $localRecords = $this->fixLocalRecords($prototype);

        foreach($results as $key => $result) {
            $result = array_change_key_case($result, CASE_LOWER);

            try {
                $result['previous_record'] = $this->provider->getPatientByMRN($result['patient']);
            } catch (PatientNotFoundException $e) {}

            $record['identifier'] = $result['hmtb_id'];
            $result['import_description'] = sprintf('%s specimen on the %s.', $result['hmtb_id'], $result['patient']);

            $record = $resolver->resolve($result);

            if (!is_null($record['patient']) && $record['restricted'] == 'No' && !in_array($record['identifier'], $localRecords)) {
                $records[] = $record;
            }

            unset($results[$key]);
        }

        return $records;
    }

    /**
     * {@inheritdoc}
     */
    public function configureResolver(OptionsResolverInterface $resolver)
    {
        parent::configureResolver($resolver);

        $resolver->setRequired(array(
            'hmtb_id',
            'sample_type',
            'patient',
            'diagnosis'
        ));

        $resolver->setOptional(array(
                                     '0',
                                     '1',
                                     '2',
                                     'subtype', 
                                     'restricted',
                                     'total_sum_vials_remaining', 
                                     'blasts', 
                                     'ct_cycle',
                                     'ct_study_day', 
                                     'ct_peak_through', 
                                     'ct_time_post_drug', 
                                     'ct_time_post_drug_unit', 
                                     'ct_treatment_relation_time',
                                     'when_modified'));

        $resolver->setAllowedTypes(array(
            'hmtb_id' => array('string'),
            'restricted' => array('string'),
        ));

        $resolver->setAllowedValues(array(
            $sample_type = array('BM-MNC', 'PB-MNC', 'Phereis'),
            $restricted = array('No', 'YES'),
            $diagnosis = array('AML'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getCriteria(array $history)
    {
        if (empty($history)) {
            return;
        }

        $criteria = $history[0]->getCriteria();

        $stmt = $this->connection->prepare($this->getMaxCollectionId());
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $newLastId = $result['MAX'];
        $stmt->closeCursor();

        return array(
            'first_id' => $criteria['last_id'],
            'last_id' => $newLastId,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultCriteria()
    {
        $stmt = $this->connection->prepare($this->getMaxCollectionId());
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $newLastId = $result['MAX'];
        $stmt->closeCursor();

        return array(
            'first_id' => 0,
            'last_id' => $newLastId,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fixLocalRecords(PrototypeInterface $prototype)
    {
        $sql = $this->getHasFieldSQL($prototype);
        $stmt = $this->defaultConnection->prepare($sql);
        $stmt->execute();
        $localRecords = $stmt->fetchAll();
        $stmt->closeCursor();  

        //Cleansing localRecords to a simple array of htmb-ids that are existant in the local database
        $hmtbIds = array();
        foreach($localRecords as $localRecord) {
            $hmtbIds[] = $localRecord['stringValue'];
        }

        return $localRecords;
    }

    /**
     * {@inheritdoc}
     */
    private function getHasFieldSQL(PrototypeInterface $prototype)
    {
        /**
         * Need to get all hmtb id's that are stored locally.
         */
        $hmtbFieldId = $prototype->getFieldByName('hmtb-id')->getId();

        $sql = "SELECT v.stringValue
            FROM accard_sample_proto_fldval AS v
            LEFT JOIN accard_sample AS a ON (v.sampleId = a.id)
            WHERE v.fieldId IN (%s)";

        return sprintf($sql, $hmtbFieldId);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'hmtb_specimens_collection';
    }

    /**
     * Get SQL statement.
     *
     * @return string
     */
    private function getSQL()
    {
        return "SELECT
                COLLECTION_ID AS HMTB_ID,
                MEDRECNUM AS PATIENT,
                COLLECTION_ID AS IDENTIFIER,
                TO_CHAR(COLLECTED_DATEDRAWN, 'mm/dd/yyyy') AS ACTIVITY_DATE,
                SAMPLETYPE AS SAMPLE_TYPE,
                RESTRICTEDACCESS_CHECKBOX AS RESTRICTED,
                DIAGNOSIS,
                SUBTYPE,
                TOTAL_SUM_VIALSREMAINING AS TOTAL_SUM_VIALS_REMAINING,
                BLASTS,
                CT_CYCLE,
                CT_STUDYDAY AS CT_STUDY_DAY,
                CT_PEAKTROUGH AS CT_PEAK_THROUGH,
                CT_TIMEPOSTDRUG AS CT_TIME_POST_DRUG,
                CT_TIMEPOSTDRUG_UNIT AS CT_TIME_POST_DRUG_UNIT,
                CT_TIMEINRELATIONTOTREATMENT AS CT_TREATMENT_RELATION_TIME,
                WHENMODIFIED AS WHEN_MODIFIED
            FROM HMTB.INVENTORY_VW
            WHERE COLLECTION_ID > :first_id AND COLLECTION_ID <= :last_id
            ORDER BY MEDRECNUM";
    }

    /**
     * Find the biggest collection id SQL.
     * 
     * @return string
     */
    private function getMaxCollectionId()
    {
        return "SELECT MAX(COLLECTION_ID) AS MAX FROM HMTB.INVENTORY_VW";
    }
}
