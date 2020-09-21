<?php
    class Entity {
        // input could be either data from database or entity id
        // This is to be able to create entity objects from id or entity from table
        private $con, $sqlData;

        public function __construct($con, $input) {
            $this -> con = $con;

            if(is_array($input)) {
                $this -> sqlData = $input;
            }
            // sqlData is an entity id not an array
            // in which case we get all the data associated with id from db
            else {
                $query = $this->con->prepare("SELECT * FROM entities WHERE id = :id");
                $query->bindValue(":id", $input);
                $query->execute();

                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            }
        }

        public function getId() {
            return $this -> sqlData["id"];
        }

        public function getName() {
            return $this -> sqlData["name"];
        }

        public function getThumbnail() {
            return $this -> sqlData["thumbnail"];
        }

        public function getPreview() {
            return $this -> sqlData["preview"];
        }

        public function getCategoryId() {
            return $this -> sqlData['categoryId'];
        }

        public function getSeasons() {
            $query = $this -> con -> prepare("SELECT * FROM videos WHERE entityId =:id AND 
                                            isMovie = 0 ORDER BY season, episode ASC");
            $query->bindValue(":id", $this->getId());
            $query->execute();

            $seasons = array();
            $videos = array();
            $currentSeason = null;
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                if($currentSeason != null && $currentSeason != $row["season"]) {
                    $seasons[] = new Season($currentSeason, $videos);
                    $videos = array();
                }

                $currentSeason = $row["season"];
                $videos[] = new Video($this -> con, $row);
            }

            if(sizeof($videos) != 0) {
                $seasons[] = new Season($currentSeason, $videos);
            }

            return $seasons;
        }
    }
?>