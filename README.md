# API
## Install
### In the .env file
1. In DATABASE URL put your database url.
2. To configure the sending of mail replace in MAILER_URL.
3. Download dependencies to compose.

### Before you put the api online
* Configure the .env file
* Type the command `php bin/console d:d:c` this will create the database.
* Make the `php bin/console m:m` command for the migration.
* Then end up with the `php bin/console d:m:m` command to copy your migration into the database.
* Launch fixtures with the `php bin/console d:f:l` command if you want to do some testing.
* Do your tests.

[![Maintainability](https://api.codeclimate.com/v1/badges/72509f5c509a66e3ebc0/maintainability)](https://codeclimate.com/github/Monsieur76/S7/maintainability)
