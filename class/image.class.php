<?php

class image
{
    const STATUS_NEW = "NEW";
    const STATUS_COMPLETE = "COMPLETE";
    const STATUS_EXPIRED = "EXPIRED";
    const STATUS_ERROR = "ERROR";

    public $id = 0;
    public $filename = "";
    public $timestamp = 0;
    public $expire_timestamp = 0;
    public $status = STATUS_NEW;
    public $status_message = "";

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public static function get_expired($db)
    {
        $query = "SELECT id, filename, timestamp, expire_timestamp, status FROM image WHERE status <> :status AND expire_timestamp <> 0 AND expire_timestamp < :time";
        $results = $db->execute_prepared($query, array("time"=>time(), "status"=>self::STATUS_EXPIRED), true);

        $image_array = array();
        if (count($results) > 0)
        {
            foreach ($results as $result)
            {
                $image_array[] = (new image($db))->hydrate($result);
            }
        }

        return $image_array;
    }

    public function set_status($status, $status_message = '')
    {
        if ($this->status != $status)
        {
            $query = "UPDATE image SET status = :status, status_message = :status_message WHERE id = :id LIMIT 1";
            $result = $this->db->execute_prepared($query, array("id"=>$this->id, "status"=>$status, "status_message"=>$status_message));

            $this->status = $status;
        }
    }


    public function burn()
    {
        $file_path = $this->get_filepath();
        if (file_exists($file_path))
        {
            if (!unlink($file_path))
            {
                $status_message = "Failed to delete image";
                $this->set_status(self::STATUS_ERROR, $status_message);

                throw new Exception($status_message);
            }
        }

        $this->set_status(self::STATUS_EXPIRED);
    }

    public function get_filepath()
    {
        return STORAGE_PATH."/".$this->filename;
    }

    public function hydrate($result)
    {
        $this->id = $result->id;
        $this->filename = $result->filename;
        $this->timestamp = $result->timestamp;
        $this->expire_timestamp = $result->expire_timestamp;
        $this->status = $result->status;
        $this->status_message = $result->status_message;

        return $this;
    }

    public function save()
    {
        $query = "
            UPDATE 
                image 
            SET 
                filename=:filename,
                timestamp=:timestamp,
                expire_timestamp=:expire_timestamp,
                status=:status,
                status_message=:status_message
            WHERE
                id = :id
        ";
        $params = array(
            'id' => $this->id,
            'filename' => $this->filename,
            'timestamp' => $this->timestamp,
            'expire_timestamp' => $this->expire_timestamp,
            'status' => $this->status,
            'status_message' => $this->status_message
        );

        $this->db->execute_prepared($query, $params);
    }

    public function insert()
    {
        $query = "
            INSERT INTO 
                image (
                    filename, 
                    timestamp, 
                    expire_timestamp, 
                    status,
                    status_message
                )
            VALUES 
                (
                    :filename, 
                    :timestamp, 
                    :expire_timestamp, 
                    :status,
                    :status_message
                )
        ";
        $params = array(
            'filename' => $this->filename,
            'timestamp' => $this->timestamp,
            'expire_timestamp' => $this->expire_timestamp,
            'status' => $this->status,
            'status_message' => $this->status_message
        );

        $this->db->execute_prepared($query, $params);

        $this->id = $this->db->connection->lastInsertId();
    }
}