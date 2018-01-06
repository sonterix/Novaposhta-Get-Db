# Novaposhta-Get-Db
- install [Composer](https://getcomposer.org/)
- run **"Composer install"**
- create your own **"config.php"** from **"config.default.php"** and fill variable values in this file
- add in **'vendor/composer/autoload_pse4.php'** following lines (*this needed for correct namespaces in project*)
  - 'app\\controller\\' => array($baseDir . '/src/controller'), 
  - 'app\\model\\' => array($baseDir . '/src/model') 
  
# Methods List
- **getCities** ( */np/getCities* ) - getting a list of **Cities** and writing to the table **"np_cities"**
- **getDepartments** ( */np/getDepartments* ) - getting a list of **Departments** and writing to the table **"np_departments"**
- **getCargoTypes** ( */np/getCargoTypes* ) - getting a list of **Cargo Types**
