## Purpose

  The module helps developers who work with remote databases or need to import databases frequently to the local machine. It allows to store system config settings in the local file system and override database values.

 ### Description

  Suppose you work with a live project that has live credentials for payment gateways or shipping methods. When importing the database locally, you don’t want to keep those live credentials since they probably won’t work on your machine and it’s not a secure practice overall - you got to have sandbox account details for testing purposes. 
  
  In order not to change those settings each time you update your database with a fresh copy from the live environment, you can just store the local configuration in the file - it will automatically hook up and thus save you time which you’d better use on more important things.

## Installation

This module is composer ready so you can install it via command (do not forget to add this repo to the composer.json before):

```sh
composer require justcoded/local-config:*
```

## Usage

The module has basic settings to specify the location of a local config file or a remote config file. Local config file has a higher priority, and in case you specify local and remote files, the settings will be hooked up from the local one.

Module is configured at `Stores/Configuration/JustCoded/Local Config`

Then just put the file `.env.system-config` (you can change name of the file from settings) in the root Magento folder with your required settings, for example:

```env
catalog/frontend/grid_per_page=43
catalog/frontend/grid_per_page_values=9,15,30,43,56
```

If you want to override the settings of the remote database, your local config file will look like this:

```env
web/unsecure/base_url=http://local_magento.local/
web/unsecure/base_link_url=http://local_magento.local/
web/secure/base_url=http://local_magento.local/
web/secure/base_link_url=http://local_magento.local/
```

## Notes

Try to keep only one local environment file and do not commit it to version control.
The file should only include your local environment values such as database passwords or API keys. Your production database should have a different password than your development database.

## Compatibility
Fully tested with Magento 2.1.6

## Contact

Follow our blog at http://justcoded.com/blog/

## Maintainers

- [Oleg Biriukov](<obirukov@justcoded.co>)

## License

The MIT License (MIT)

Copyright © 2017 JustCoded

Permission is hereby granted free of charge to any person obtaining a copy of this software and associated documentation files (the "Software") to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.