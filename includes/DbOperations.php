<?php
class DbOperations
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';

        $db = new DbConnect;



        date_default_timezone_set("Europe/Stockholm");

        $this->con = $db->connect();

    }
    public function specificType($type)
    {

        $stmt = $this->con->prepare("SELECT * FROM test.tbl_events WHERE type = ?");

        $stmt->bind_param("s", $type);


        $stmt->execute();

        $crime = $this->get_lists($stmt);

        return $crime;
    }

    public function lastTwoWeeksCrimes()
    {

        $stmt = $this->con->prepare("SELECT COUNT(type) AS count, datetime 
        FROM tbl_events  WHERE datetime BETWEEN ? AND ? GROUP BY datetime");
            $date = date("Ymd");
            $date2 = date("Ymd", strtotime("-2 weeks"));
            $stmt->bind_param("ss", $date2, $date);

        $stmt->execute();

        $courses = $this->get_lists($stmt);

        return $courses;
    }
    public function getCounties()
    {

        $stmt = $this->con->prepare("SELECT DISTINCT location_name FROM tbl_events
        WHERE location_name LIKE '%län%'  
        ORDER BY `tbl_events`.`location_name`  ASC");

        $stmt->execute();

        $counties = $this->get_lists($stmt);

        return $counties;
    }
/*     public function generateGeoJSON()
    {
        $conn = new PDO('mysql:host=localhost:3306;dbname=test','root','');
        $sql = 'SELECT id, latitude AS x, longitude AS y FROM tbl_events WHERE datetime BETWEEN "2020-02-13" AND "2020-02-13"';
        $rs = $conn->query($sql);
        if (!$rs) {
            echo 'An SQL error occured.\n';
            exit;
        }
        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => array()
         );
# Loop through rows to build feature arrays
while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
    $properties = $row;
    # Remove x and y fields from properties (optional)
    unset($properties['x']);
    unset($properties['y']);
    $feature = array(
        'type' => 'Feature',
        'geometry' => array(
            'type' => 'Point',
            'coordinates' => array(
                $row['x'],
                $row['y']
            )
        ),
        'properties' => $properties
    );
    # Add feature arrays to feature collection array
    array_push($geojson['features'], $feature);
}
        return $geojson;

    } */


    public function getLocationData()
    {

        $stmt = $this->con->prepare("SELECT * FROM tbl_events WHERE not type = 'Övrigt' AND NOT type = 'Sammanfattning natt' AND datetime BETWEEN ? AND ?");
        $date = date("Ymd");
        $date2 = date("Ymd", strtotime("-1 weeks"));
        $stmt->bind_param("dd", $date2, $date);

        $stmt->execute();

        $coordinates = $this->get_lists($stmt);

        return $coordinates;
    }
    public function getEvent($id)
    {
        

        $stmt = $this->con->prepare("SELECT * FROM tbl_events WHERE id = ? AND not type = 'Övrigt' AND NOT type = 'Sammanfattning natt' AND datetime BETWEEN ? AND ?");
        $date = date("Ymd");
        $date2 = date("Ymd", strtotime("-1 weeks"));
        $stmt->bind_param("sdd",$id, $date2, $date);

        $stmt->execute();

        $info = $this->get_lists($stmt);

        return $info;
    }
    //------------------------------------------------------------------

    private function get_one_record($stmt)
    {

        $result = $stmt->get_result();

        if ($result->num_rows === 0) exit('No rows');

        $user = array();

        while ($row = $result->fetch_assoc()) {

            $user = $row;
        }



        return $user;
    }


    private function get_lists($stmt)
    {

        $result = $stmt->get_result();

        if ($result->num_rows === 0) return 'No rows';

        $objs = array();

        while ($row = $result->fetch_assoc()) {

            $objs[] = $row;
        }



        return $objs;
    }
}
