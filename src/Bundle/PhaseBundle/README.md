Phase Bundle
============

The Accard framework phase bundle provides mappings and utilities wrapped around
the phase component. Phases are temporal spans within the context of another
provided domain resource. This bundle does not provide a fully-operational set
of phases. Instead, it contains a base set of interfaces and abstract an
abstract implemntation of a phase to be included within another domain resource.

Accard
------

Modern medical patient repository implementation in PHP and Symfony2. See
[the project homepage](http://accard.med.upenn.edu) for more details.

Testing
-------

Accard uses [PHPSpec](http://phpspec.net) to facilitate BDD style development.
We do not include the tool in our development dependencies with
[Composer](http://getcomposer.org), in favor of a global install. To install
PHPSpec and run the bundle specifications:

```bash
$ composer global require 'phpspec/phpspec' '*'
$ composer install
$ phpsec run -f pretty
```

Documentation
-------------

Visit the [Accard documentation](http://accard.med.upenn.edu/docs) for further
instruction and explainations.

License
-------

Accard is distributed under the MIT license, and exists within each repository
at Resources/meta/LICENSE.

Authors
-------

Accard is developed at [The University of Pennsylvania](http://upenn.edu) by the
Database and Applications Group.