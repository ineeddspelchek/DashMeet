<?php
/**
 * Database Class
 *
 * Contains connection information to query PostgresSQL.
 */

class Database {
    private $dbConnector;

    /**
     * Constructor
     *
     * Connects to PostgresSQL
     */
    public function __construct() {
        $host = "nope";
        $port = "nope";
        $database = "nope";
        $user = "nope";
        $password = "nope";
        $endpoint = "nope";
    
        $this->dbConnector = pg_pconnect("postgres://$user:$password@$host/$database?options=endpoint%3D$endpoint");
    }

    /**
     * Query
     *
     * Makes a query to posgres and returns an array of the results.
     * The query must include placeholders for each of the additional
     * parameters provided.
     */
    public function query($query, ...$params) {
        $res = pg_query_params($this->dbConnector, $query, $params);

        if ($res === false) {
            echo pg_last_error($this->dbConnector);
            return false;
        }

        return pg_fetch_all($res);
    }
}
