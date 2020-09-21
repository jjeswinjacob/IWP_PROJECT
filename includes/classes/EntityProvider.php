<?php
    class EntityProvider {
        public static function getEntities($con, $categoryId, $limit) {
            $sql = "SELECT * FROM entities ";

            if($categoryId != null) {
                $sql .= "WHERE categoryId=:categoryId ";
            }

            $sql .= "ORDER BY RAND() LIMIT :limit";

            $query = $con -> prepare($sql);

            if($categoryId != null) {
                $query -> bindValue(":categoryId", $categoryId);
            }
            $query -> bindValue(":limit", $limit, PDO::PARAM_INT);
            $query -> execute();

            $result = array();
            
            // Fetch data and store in associative array
            // which is a key value store
            while($row = $query -> fetch(PDO :: FETCH_ASSOC)) {
                $result[] = new Entity($con, $row);
            }

            return $result;
        }
    }
?>