# Dwsync package

## Installation
* Clone repo : `git clone https://github.com/hnidev/dwsync`
* Install provider in config/app.php
* Configure main composer.json

Edit `composer.json` to reflect your package information.  At a minimum, you will need to change the package name and autoload lines so that "vendor/package" reflects your new package's name and namespace.

```json
{
    "autoload": {
        "psr-4": {
            "Hni\\Dwsync\\": "packages/hni/dwsync/src"
        }
    }
}
```
* migrate : 
`php artisan migrate --package="hni/dwsync"` (not working with 5.x)
`php artisan migrate --path=/packages/hni/dwsync/migrations`

*  Populate with seeds
`php artisan db:seed --class="Hni\Dwsync\Seeds\DwEntityTypeSeed"`

## Available Commands
* publish everything : `php artisan vendor:publish --provider="Hni\Dwsync\DwsyncServiceProvider"`
* publish config only : `php artisan vendor:publish --provider="Hni\Dwsync\DwsyncServiceProvider" --tag=config`
* publish views only : `php artisan vendor:publish --provider="Hni\Dwsync\DwsyncServiceProvider" --tag=view`
* publish migrations only : `php artisan vendor:publish --provider="Hni\Dwsync\DwsyncServiceProvider" --tag=migration`
* publish seeds only : `php artisan vendor:publish --provider="Hni\Dwsync\DwsyncServiceProvider" --tag=seed`

## Override views
* publish views
* turn config in 'dwsync.overrideViews' to *true*
* modify views in 'views/dwsync/'

It is recommended to not override views, but bring changes directly in "Hni\Dwsync" package

## Cron
* to run syncing for :
    ** existing dwProject, use this URL `{your domaine}/dwsync/dwProjects/syncing/all`
    ** all marked as "autoSync > 0" only, use `{your domaine}/dwsync/dwProjects/sync/all/marked`
* you can add it to crontab if needed
* you can implement scheduling feature with [Laravel schedule](https://laravel.com/docs/5.5/scheduling)

This is the suggestion for crontab if Idnr has huge records
```yaml
##Run idnr twice per day:
## vil3 : project_id =10
? ? * * * curl … dwProjects/sync/10

## fermier : project_id =21
? ? * * * curl … dwProjects/sync/21

### Run all marked ‘autosync’ projects : Questionnaires, …
? ? * * * curl …  dwsync/dwProjects/sync/all/marked

```
## Resources
* [Package migration & seeding](https://websanova.com/blog/laravel/creating-a-new-package-in-laravel-5-part-4-database-migrations-and-seeding)

## Adding new project to syncing
These are main steps :
1. add new project (create menu in 'dw projects' page)
    * Where to find the `quest code` and `long quest code`? sign in here [https://app.datawinners.com/xforms/formList](https://app.datawinners.com/xforms/formList).
    * `credential` use DW API pattern `{mail_id}:{password}`
2. add new questions for created projects (click 'dw projects' page, then hit 'extra' buton in `actions` column)
3. pull questions: from submissions (Datasender or Idnr or Simple questionaire), or from xlsform (Advanced questionire). Check, then insert found questions.
4. sync data

## Export/Import all created projects & questions
### Export
What if we want to export all DW projects & questions to another teammate computer?
Follow thhis:
* Clean your local data for dw_projects, dw_questions (remove)
* Create reverse seeds (with [iseed](https://github.com/orangehill/iseed)):
`php artisan iseed dw_projects,dw_questions --force`
Note: You can alternatively export data from particular project (dw_submissionX) to avoid whole syncing when import.
* The above cmd will add seed in `database/seeds/DatabaseSeeder.php`, reset that:
`git checkout database/seeds/DatabaseSeeder.php`
* Export all existing dw_submission table (related to each project): All DB structure of each project (dw_submissionX tables)

### Import
* Update you project (pull)
* Run `composer dump-autoload` to consider new seed files
* Run dw_projects & dw_questions seed (fixtures from iseed)
```bash
php artisan db:seed --class=DwProjectsTableSeeder
php artisan db:seed --class=DwQuestionsTableSeeder

#Do the same for particular project's seed if exists
```
* Run all DB structure for each project to create related dw_submission tables

## Update existing questionnaire
### Changes from DW
* Select the project, go to extra actions page
* Pull questions & insert (you can remove all related questions if needed, but be aware 'cause current  questions may already have extra flags such as **isValid**)
### Local changes
You may need to add extra column to track a specific info (eg: flag a row with **softdeleted** info). To do that:
1. update the DB structure of you questionnaire (I assume, it is **dw_submissionX**)
2. add you question into dw_question too, put its name in questionId
3. go to the project admin page, then to extra actions page
4. select menu **update your model from DB structure**. This will update you model source code
5. remove duplicates menu & route for this current model in `routes/dynamic_api.php`, `routes/dynamic_web.php`, `resources/views/partials/dynamic_menu.blade.php`
6. don't forget to export the DB structure of `dw_submissionX` to sql (we need to track it for deployment)
7. export dw_question seed : `php artisan iseed dw_questions --force`

## Using SMS helper
1. Add DW sms account in `.env`, something like `DW_SMS_ACCOUNT=my_sms_api_tester:my_testerapisms`
1. Test from url `dwsync/dwSms/send?num=[your num]&content=[your content]`
1. Use the SMS helper