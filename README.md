# chubbyphp/chubbyphp-api-slim-skeleton

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-api-slim-skeleton.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-api-slim-skeleton)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-api-slim-skeleton/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-api-slim-skeleton)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-api-slim-skeleton/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-api-slim-skeleton)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-api-slim-skeleton/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-api-slim-skeleton/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-api-slim-skeleton/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-api-slim-skeleton/?branch=master)

## Description

A slim 3 skeleton to build web apis.

## Requirements

 * php: ~7.0
 * [chubbyphp/chubbyphp-api-http][1]: ~1.0@beta>=1.0-beta9
 * [chubbyphp/chubbyphp-deserialization][2]: ^1.1.1
 * [chubbyphp/chubbyphp-deserialization-model][3]: ~1.1
 * [chubbyphp/chubbyphp-lazy][4]: ~1.1
 * [chubbyphp/chubbyphp-model][5]: ^3.0.1
 * [chubbyphp/chubbyphp-model-doctrine-dbal][6]: ^1.0.2
 * [chubbyphp/chubbyphp-serialization][7]: ^1.0.2
 * [chubbyphp/chubbyphp-translation][8]: ^1.1.2
 * [chubbyphp/chubbyphp-validation][9]: ~2.1
 * [chubbyphp/chubbyphp-validation-model][10]: ^1.0.2
 * [monolog/monolog][11]: ~1.5
 * [ramsey/uuid][12]: 2.0
 * [silex/providers][13]: ^2.0.3
 * [slim/slim][14]: ~3.0
 * [symfony/console][15]: ~2.7|~3.0
 * [symfony/yaml][16]: ~2.7|~3.0

## Installation

### With vagrant-php

#### Install `create-slim3-project` command

[create-slim3-project][17]

#### Create project

```{.sh}
create-slim3-project --name=myproject --vagrantIp=10.15.10.15
```

### With php on host

```{.sh}
composer create-project chubbyphp/chubbyphp-api-slim-skeleton myproject dev-master --prefer-dist
```

## Setup

### Create database

```{.sh}
bin/console chubbyphp:model:dbal:database:create
```

### Create / Update schema

```{.sh}
bin/console chubbyphp:model:dbal:database:schema:update --dump --force
```

[1]: https://github.com/chubbyphp/chubbyphp-api-http
[2]: https://github.com/chubbyphp/chubbyphp-deserialization
[3]: https://github.com/chubbyphp/chubbyphp-deserialization-model
[4]: https://github.com/chubbyphp/chubbyphp-lazy
[5]: https://github.com/chubbyphp/chubbyphp-model
[6]: https://github.com/chubbyphp/chubbyphp-model-doctrine-dbal
[7]: https://github.com/chubbyphp/chubbyphp-serialization
[8]: https://github.com/chubbyphp/chubbyphp-translation
[9]: https://github.com/chubbyphp/chubbyphp-validation
[10]: https://github.com/chubbyphp/chubbyphp-validation-model
[11]: https://github.com/Seldaek/monolog
[12]: https://github.com/ramsey/uuid
[13]: https://github.com/silexphp/Silex-Providers
[14]: https://github.com/slimphp/Slim
[15]: https://github.com/symfony/console
[16]: https://github.com/symfony/yaml
[17]: https://github.com/vagrant-php/create-slim3-project
