<?php


/**
 * BuilderDatabaseManagerController
 * 
 * Controller to handle database management API requests
 */
class BuilderDatabaseManagerController
{
    /**
     * Get a list of all databases
     * 
     * @return array Response data
     */
    public static function getDatabases()
    {
        $databases = array_keys(BuilderDatabaseManagerService::getDatabases());
        
        return [
            'type' => 'DATABASE_LIST',
            'data' => $databases
        ];
    }
    
    /**
     * Get a list of tables for a specific database
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function getTables(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        
        if (!$databaseName) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name is required',
                'code' => 400
            ];
        }
        
        $tables = BuilderDatabaseManagerService::getTables($databaseName);
        
        return [
            'type' => 'TABLE_LIST',
            'data' => [
                'databaseName' => $databaseName,
                'tables' => $tables
            ]
        ];
    }
    
    /**
     * Get the structure of a specific table
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function getTableStructure(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        $tableName = $request->get('tableName') ?? null;
        
        if (!$databaseName || !$tableName) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and table name are required',
                'code' => 400
            ];
        }
        
        $structure = BuilderDatabaseManagerService::getTableStructure($databaseName, $tableName);
        
        if (!$structure) {
            return [
                'type' => 'ERROR',
                'message' => "Table structure for {$tableName} in {$databaseName} not found",
                'code' => 404
            ];
        }
        
        return [
            'type' => 'TABLE_STRUCTURE',
            'data' => [
                'databaseName' => $databaseName,
                'tableName' => $tableName,
                'structure' => $structure
            ]
        ];
    }
    
    /**
     * Get the complete structure of a database
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function getDatabaseStructure(?Request $request = null)
    {
        $databaseName = $request->get('databaseName') ?? null;
        
        if (!$databaseName) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name is required',
                'code' => 400
            ];
        }
        
        $structure = BuilderDatabaseManagerService::getDatabaseStructure($databaseName);
        
        return [
            'type' => 'DATABASE_STRUCTURE',
            'data' => [
                'databaseName' => $databaseName,
                'structure' => $structure
            ]
        ];
    }
    
    /**
     * Get table data with pagination
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function getTableData(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        $tableName = $request->get('tableName') ?? null;
        $limit = $request->get('limit') !== null ? (int)$request->get('limit') : 500;
        $offset = $request->get('offset') !== null ? (int)$request->get('offset') : 0;
        

        if (!$databaseName || !$tableName) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and table name are required',
                'code' => 400
            ];
        }


        $data = BuilderDatabaseManagerService::getTableData($databaseName, $tableName, $limit, $offset);
        
        return [
            'type' => 'TABLE_DATA',
            'data' => [
                'databaseName' => $databaseName,
                'tableName' => $tableName,
                'records' => $data['records'],
                'columns' => $data['columns'],
                'foreignKeys' => $data['foreignKeys'],
                'totalCount' => $data['totalCount'],
                'limit' => $limit,
                'offset' => $offset
            ]
        ];
    }
    
    /**
     * Get filtered table data
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function getFilteredTableData(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        $tableName = $request->get('tableName') ?? null;
        $filters = $request->get('filters') ?? [];
        $limit = $request->get('limit') !== null ? (int)$request->get('limit') : 500;
        $offset = $request->get('offset') !== null ? (int)$request->get('offset') : 0;
        
        if (!$databaseName || !$tableName) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and table name are required',
                'code' => 400
            ];
        }
        
        $data = BuilderDatabaseManagerService::getFilteredTableData($databaseName, $tableName, $filters, $limit, $offset);
        
        return [
            'type' => 'FILTERED_DATA',
            'data' => [
                'databaseName' => $databaseName,
                'tableName' => $tableName,
                'records' => $data['records'],
                'columns' => $data['columns'],
                'totalCount' => $data['totalCount'],
                'filters' => $filters
            ]
        ];
    }
    
    /**
     * Execute a SQL query
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function executeQuery(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        $query = $request->get('query') ?? null;
        
        if (!$databaseName || !$query) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and query are required',
                'code' => 400
            ];
        }
        
        try{
            $results = BuilderDatabaseManagerService::executeQuery($databaseName, $query);
        }
        catch(Exception $e)
        {
            return [
                'type' => 'ERROR',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
        
        return [
            'type' => 'QUERY_RESULTS',
            'data' => [
                'databaseName' => $databaseName,
                'query' => $query,
                'results' => $results
            ]
        ];
    }

    /**
     * Save a SQL query
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function saveQuery(Request $request)
    {
        $databaseName = $request->get('database') ?? null;
        $query = $request->get('query') ?? null;
        
        if (!$databaseName || !$query) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and query are required',
                'code' => 400
            ];
        }
        
        $id = BuilderDatabaseManagerService::saveQuery($databaseName, $query, $request->get('name'));
        
        return [
            'type' => 'QUERY_SAVED',
            'data' => [
                'databaseName' => $databaseName,
                'query' => $query,
                'id' => $id,
                'name' => $request->get('name')
            ]
        ];
    }

    /**
     * Save a SQL query
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function updateSavedQuery(Request $request)
    {
        $databaseName = $request->get('database') ?? null;
        $query = $request->get('query') ?? null;
        
        if (!$databaseName || !$query) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and query are required',
                'code' => 400
            ];
        }
        
        BuilderDatabaseManagerService::updateSavedQuery($databaseName, $request->get('id'), $request->get('name'), $query);
        
        return [
            'type' => 'QUERY_UPDATED',
            'data' => [
                'databaseName' => $databaseName,
                'name' => $request->get('name')
            ]
        ];
    }

    /**
     * Get saved queries
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function getSavedQueries(Request $request)
    {
        $databaseName = $request->get('database') ?? null;
        
        if (!$databaseName) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name is required',
                'code' => 400
            ];
        }
        
        $result = BuilderDatabaseManagerService::getSavedQueries($databaseName);
        
        return [
            'type' => 'SAVED_QUERIES',
            'data' => [
                'databaseName' => $databaseName,
                'queries' => $result,
                'name' => $request->get('name')
            ]
        ];
    }

    /**
     * Delete a saved SQL query
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function deleteSavedQuery(Request $request)
    {
        $databaseName = $request->get('database') ?? null;
        $queryId = $request->get('id') ?? null;
        
        if (!$databaseName || !$queryId) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and query id required',
                'code' => 400
            ];
        }
        
        BuilderDatabaseManagerService::deleteSavedQuery($databaseName, $queryId);
        
        return [
            'type' => 'QUERY_DELETED',
            'data' => [
                'databaseName' => $databaseName,
                'queryId' => $queryId
            ]
        ];
    }
    
    
    /**
     * Apply structure changes to a table
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function applyStructureChanges(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        $tableName = $request->get('tableName') ?? null;
        $changes = $request->get('changes') ?? null;
        
        if (!$databaseName || !$tableName || !$changes) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name, table name, and changes are required',
                'code' => 400
            ];
        }
        
        $result = BuilderDatabaseManagerService::applyStructureChanges($databaseName, $tableName, $changes);
        
        return [
            'type' => 'STRUCTURE_CHANGED',
            'data' => $result
        ];
    }
    
    /**
     * Create a new table
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function createTable(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        $tableDefinition = $request->get('tableDefinition') ?? null;
        
        if (!$databaseName || !$tableDefinition) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and table definition are required',
                'code' => 400
            ];
        }
        
        $result = BuilderDatabaseManagerService::createTable($databaseName, $tableDefinition);
        
        return [
            'type' => 'TABLE_CREATED',
            'data' => $result
        ];
    }
    
    /**
     * Delete a table
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function deleteTable(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        $tableName = $request->get('tableName') ?? null;
        
        if (!$databaseName || !$tableName) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and table name are required',
                'code' => 400
            ];
        }
        
        $result = BuilderDatabaseManagerService::deleteTable($databaseName, $tableName);
        
        return [
            'type' => 'TABLE_DELETED',
            'data' => $result
        ];
    }
    
    /**
     * Export table data to a file
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function exportData(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        $tableName = $request->get('tableName') ?? null;
        $format = $request->get('format') ?? 'csv';
        
        if (!$databaseName || !$tableName) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name and table name are required',
                'code' => 400
            ];
        }
        
        $result = BuilderDatabaseManagerService::exportData($databaseName, $tableName, $format);
        
        return [
            'type' => 'EXPORT_READY',
            'data' => $result
        ];
    }
    
    /**
     * Import data to a table
     * 
     * @param Request $request Request object
     * @return array Response data
     */
    public static function importData(Request $request)
    {
        $databaseName = $request->get('databaseName') ?? null;
        $tableName = $request->get('tableName') ?? null;
        $data = $request->get('data') ?? null;
        
        if (!$databaseName || !$tableName || !$data) {
            return [
                'type' => 'ERROR',
                'message' => 'Database name, table name, and data are required',
                'code' => 400
            ];
        }
        
        $result = BuilderDatabaseManagerService::importData($databaseName, $tableName, $data);
        
        return [
            'type' => 'IMPORT_FINISHED',
            'data' => $result
        ];
    }
}
