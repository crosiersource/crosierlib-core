CREATE DATABASE crosierlibcore_dev CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci';
CREATE USER 'crosierlibcore_dev'@'%' IDENTIFIED BY 'crosierlibcore_dev';
-- ALTER USER 'crosierlibcore_dev'@'localhost' IDENTIFIED BY 'crosierlibcore_dev';
GRANT ALL PRIVILEGES ON crosierlibcore_dev.* TO 'crosierlibcore_dev'@'%';
FLUSH PRIVILEGES;


-- dbname=crosierlibcore_dev
-- mysql_config_editor set --login-path=$dbname --host=localhost --user=$dbname --password

-- sudo touch /usr/local/bin/$dbname
-- sudo echo "mysql --login-path=$dbname $dbname" | sudo tee /usr/local/bin/$dbname
-- sudo chmod a+x /usr/local/bin/$dbname

-- Atenção: rodar o FUNCTIONS.sql utilizando o root


