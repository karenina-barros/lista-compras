<?php

namespace Livro\Database;

use \PDO;
use \Exception;

final class Connection
{
    private function __construct(){}

    public static function open( $filename )
    {
        if( file_exists( "App/Config/{$filename}.ini" ) ){
            $db = parse_ini_file( "App/Config/{$filename}.ini" );
        }
        else{
            throw new Exception( "Arquivo '$filename' não encontrado!!" );
        }

        $user = isset( $db[ 'DB_USER' ] ) ? $db[ 'DB_USER' ] : NULL;
        $pass = isset( $db[ 'DB_PASS' ] ) ? $db[ 'DB_PASS' ] : NULL;
        $name = isset( $db[ 'DB_NAME' ] ) ? $db[ 'DB_NAME' ] : NULL;
        $host = isset( $db[ 'DB_HOST' ] ) ? $db[ 'DB_HOST' ] : NULL;
        $sgbd = isset( $db[ 'DB_SGBD' ] ) ? $db[ 'DB_SGBD' ] : NULL;
        $port = isset( $db[ 'port' ] ) ? $db[ 'port' ] : NULL;

        switch( $sgbd ){
            case 'psql':
                $port = $port ? $port : '5432';
                $conn = new PDO( "pgsql:dbname={$name}; user={$user}; password={$pass}; host={$host}; port={$port} ");
                break;

            case 'mysql':
                $port = $port ? $port : '3306';
                $conn = new PDO( "$sgbd:host={$host};port={$port};dbname={$name}", $user, $pass );
                break;

            case 'sqlite':
                $conn = new PDO( "sqlite:{$name}" );
                $conn->query( 'PRAGMA foreing_keys = ON' );
                break;

            case 'ibase':
                $conn = new PDO( "firebird:dbname={$name}", $user, $pass );
                break;

            case 'oci8':
                $conn = new PDO( "oci:dbname={$name}", $user, $pass );
                break;

            case 'mssql':
                $conn = new PDO( "dblib:host={$host},1433;dbname={$name}", $user, $pass );
                break;
        }
		
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return $conn;
    }
}

