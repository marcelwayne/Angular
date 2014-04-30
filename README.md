About
========================

Aisel is young open source project based on combination Symfony2 with AngularJS for frontend, project started as experiment in january 2014.
Aisel still remains not finished, but has minimal list of features for quick-start.<br/>

Project website: http://aisel.co/<br/>
Sandbox frontend: http://sandbox.aisel.co/<br/>
Sandbox administration: http://sandbox.aisel.co/administration [backenduser/backenduser]

Installation
========================

1.) cd to your website directory and download composer with:  <br/>
curl -sS https://getcomposer.org/installer | php<br/>
2.) Create project, installer will ask you dbname, username, password, etc.. <br/>
php composer.phar create-project -s dev aisel/aisel - (create project)
3.) Create databasewith GUI tool like phpmyadmin or with command<br/>
php app/console doctrine:schema:create<br/>
3.) Load initial data in database with<br/>
php app/console doctrine:fixtures:load<br/>
5.) Clear Symfony cache directory<br/>
php app/console cache:clear --env=dev<br/>
6.) Install frontend dependencies with<br/>
bower install<br/>

Important: webserver needs permissions to write cache in aisel/app/cache/<br/>

Once this steps is done you will be able to access admin section from http://websitename.dev/administration/
and frontend at http://websitename.dev/

Sitemap for search engines like Google
========================
Task: php app/console aisel:sitemap:generate<br/>
This will generate sitemap.xml file in web directory<br/>

HTML Snapshots for SEO
========================
To make JS website crawlable by google you need to generate snapshots
Snapshots handled by https://github.com/localnerve/html-snapshots<br/>
To generate snapshots run: <b>node snapshots.js</b>. This task will create snapshots in directory web/snapshots<br/>
In the end you will need to test like this:<br/>
Page: http://aisel.dev/#!/page/about-aisel/<br/>
Test: curl http://aisel.dev/\?_escaped_fragment_\=/page/about-aisel/<br/>
Full terms from google here: https://developers.google.com/webmasters/ajax-crawling/docs/specification

