DEPRECATED use https://github.com/chubbyphp/petstore

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
 * [chubbyphp/chubbyphp-api-http][1]: ~1.0@beta>=1.0-beta15
 * [chubbyphp/chubbyphp-deserialization][2]: ^1.1.1
 * [chubbyphp/chubbyphp-deserialization-model][3]: ~1.1
 * [chubbyphp/chubbyphp-lazy][4]: ~1.1
 * [chubbyphp/chubbyphp-model][5]: ^3.0.1
 * [chubbyphp/chubbyphp-model-doctrine-dbal][6]: ^1.0.2
 * [chubbyphp/chubbyphp-serialization][7]: ~1.1
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

```sh
composer create-project chubbyphp/chubbyphp-api-slim-skeleton myproject dev-master --prefer-dist
```

## Setup

### Create database

```sh
bin/console chubbyphp:model:dbal:database:create
```

### Create / Update schema

```sh
bin/console chubbyphp:model:dbal:database:schema:update --dump --force
```

## Sample Responses

### Index

#### Json

GET http://chubbyphp-api-slim-skeleton.dev/api

```json
{
    "_links": {
        "self": {
            "href": "/api",
            "method": "GET"
        },
        "courses": {
            "href": "/api/courses",
            "method": "GET"
        }
    },
    "_type": "index"
}
```

#### Xml

GET http://chubbyphp-api-slim-skeleton.dev/api

```xml
<?xml version="1.0" encoding="UTF-8"?>
<meta-type value="index">
    <meta-links>
        <self>
            <href type="string">/api</href>
            <method type="string">GET</method>
        </self>
        <courses>
            <href type="string">/api/courses</href>
            <method type="string">GET</method>
        </courses>
    </meta-links>
</meta-type>
```

#### Yaml

GET http://chubbyphp-api-slim-skeleton.dev/api

```yaml
_links:
    self:
        href: /api
        method: GET
    courses:
        href: /api/courses
        method: GET
_type: index

```

### Courses

#### Json

GET http://chubbyphp-api-slim-skeleton.dev/api/courses?page=1&perPage=1

```json
{
    "page": 1,
    "perPage": 1,
    "sort": null,
    "order": "asc",
    "_embedded": {
        "count": 1,
        "pages": 1,
        "courses": [
            {
                "id": "d480082e-ef4f-43e6-96e8-fd2b9fa613ff",
                "name": "PHP Course",
                "level": 3,
                "progress": 0.45,
                "active": true,
                "documents": [
                    {
                        "id": "28031482-6168-47f1-a78b-ea57f38d0ef0",
                        "name": "Choose the right abstraction",
                        "url": "http://test.com/choose-the-right-abstractions.pdf",
                        "_type": "document"
                    }
                ],
                "_links": {
                    "read": {
                        "href": "/api/courses/d480082e-ef4f-43e6-96e8-fd2b9fa613ff",
                        "method": "GET"
                    },
                    "update": {
                        "href": "/api/courses/d480082e-ef4f-43e6-96e8-fd2b9fa613ff",
                        "method": "PATCH"
                    },
                    "delete": {
                        "href": "/api/courses/d480082e-ef4f-43e6-96e8-fd2b9fa613ff",
                        "method": "DELETE"
                    }
                },
                "_type": "course"
            }
        ]
    },
    "_links": {
        "self": {
            "href": "/api/courses?page=1&perPage=1&order=asc",
            "method": "GET"
        },
        "create": {
            "href": "/api/courses",
            "method": "POST"
        }
    },
    "_type": "course-search"
}
```

#### Xml

GET http://chubbyphp-api-slim-skeleton.dev/api/courses?page=1&perPage=1

```xml
<?xml version="1.0" encoding="UTF-8"?>
<meta-type value="course-search">
    <page type="integer">1</page>
    <perPage type="integer">1</perPage>
    <sort></sort>
    <order type="string">asc</order>
    <meta-embedded>
        <count type="integer">1</count>
        <pages type="integer">1</pages>
        <courses>
            <meta-type value="course" key="0">
                <id type="string">d480082e-ef4f-43e6-96e8-fd2b9fa613ff</id>
                <name type="string">PHP Course</name>
                <level type="integer">3</level>
                <progress type="float">0.45</progress>
                <active type="boolean">true</active>
                <documents>
                    <meta-type value="document" key="0">
                        <id type="string">28031482-6168-47f1-a78b-ea57f38d0ef0</id>
                        <name type="string">Choose the right abstraction</name>
                        <url type="string">http://test.com/choose-the-right-abstractions.pdf</url>
                    </meta-type>
                </documents>
                <meta-links>
                    <read>
                        <href type="string">/api/courses/d480082e-ef4f-43e6-96e8-fd2b9fa613ff</href>
                        <method type="string">GET</method>
                    </read>
                    <update>
                        <href type="string">/api/courses/d480082e-ef4f-43e6-96e8-fd2b9fa613ff</href>
                        <method type="string">PATCH</method>
                    </update>
                    <delete>
                        <href type="string">/api/courses/d480082e-ef4f-43e6-96e8-fd2b9fa613ff</href>
                        <method type="string">DELETE</method>
                    </delete>
                </meta-links>
            </meta-type>
        </courses>
    </meta-embedded>
    <meta-links>
        <self>
            <href type="string">
                <![CDATA[/api/courses?page=1&perPage=1&order=asc]]>
            </href>
            <method type="string">GET</method>
        </self>
        <create>
            <href type="string">/api/courses</href>
            <method type="string">POST</method>
        </create>
    </meta-links>
</meta-type>
```

#### Yaml

GET http://chubbyphp-api-slim-skeleton.dev/api/courses?page=1&perPage=1

```yaml
page: 1
perPage: 1
sort: null
order: asc
_embedded:
    count: 1
    pages: 1
    courses:
        -
            id: d480082e-ef4f-43e6-96e8-fd2b9fa613ff
            name: 'PHP Course'
            level: 3
            progress: 0.45
            active: true
            documents:
                -
                    id: 28031482-6168-47f1-a78b-ea57f38d0ef0
                    name: 'Choose the right abstraction'
                    url: 'http://test.com/choose-the-right-abstractions.pdf'
                    _type: document
            _links:
                read:
                    href: /api/courses/d480082e-ef4f-43e6-96e8-fd2b9fa613ff
                    method: GET
                update:
                    href: /api/courses/d480082e-ef4f-43e6-96e8-fd2b9fa613ff
                    method: PATCH
                delete:
                    href: /api/courses/d480082e-ef4f-43e6-96e8-fd2b9fa613ff
                    method: DELETE
            _type: course
_links:
    self:
        href: '/api/courses?page=1&perPage=1&order=asc'
        method: GET
    create:
        href: /api/courses
        method: POST
_type: course-search
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
