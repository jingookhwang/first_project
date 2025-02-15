<?php
    declare(strict_types=1); //0은 타입 검사 비활성 , 1은 타입 검사 활성
    namespace app;
    require_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";

    use Dotenv\Dotenv;
    use Exception;
    use PDO;
    use PDOException;

    class Application{
        private PDO $connection;
        private static $instance = null;
        public function __construct()
        {
            $this->initializeEnvironment();
            $this->initializeDatabase();
        }

        public function initializeEnvironment():void{ //리턴값 없음 void 설정
            try{
                $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
                $dotenv->load();
            }catch(Exception $e){
                echo "dotenv 환경설정 실패=".$e->getMessage();
            }
        }

        public function initializeDatabase():void{
            try{    
                $dbSetting =sprintf(
                    'mysql:host=%s;dbname=%s;charset=utf8'
                    ,$_ENV['DB_HOST']
                    ,$_ENV['DB_NAME']
                );

                $this->connection = new PDO(
                    $dbSetting  , $_ENV['DB_USER'] , $_ENV['DB_PASS']
                );

                $this->connection->setAttribute(\PDO::ATTR_ERRMODE, 
                                                \PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                echo "pdo init 실패 =" . $e->getMessage();
            }
        }

        public function getConnection ():PDO {
            return $this->connection;
        }

        public static function getInstance() {
            if (self::$instance === null) {
                self::$instance = new Application();
            }
            return self::$instance;
        }
        public function run(): void {
            echo "Application가 실행 중입니다.";
        }

    }


?>