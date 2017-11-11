# mysql-backup


Simple MySQL Backup Manager build on top of mysqldump and Yii2 framework.


**Features**
<ul>
<li>Backup scheduling</li>
<li>Deleting old backups</li>
</ul>



**How To**

Install as a normal Yii2 application. Setup a cron to execute, `yii cron`. Make the frequency close to minimum backup period. That's it :)