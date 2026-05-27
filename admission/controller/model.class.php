<?php

class Model
{
    // Refer to database connection
    private $db;

    // Instantiate object with database connection
    public function __construct($db_conn)
    {
        $this->db = $db_conn;
    }
    //Insert Data
    public function insert_data($table, $data)
    {
        if (!empty($data) && is_array($data)) {
            $columns = '';
            $values  = '';
            $i = 0;


            $columnString = implode(',', array_keys($data));
            $valueString = ":" . implode(',:', array_keys($data));
            $sql = "INSERT INTO " . $table . " (" . $columnString . ") VALUES (" . $valueString . ")";
            $query = $this->db->prepare($sql);
            foreach ($data as $key => $val) {
                $query->bindValue(':' . $key, $val);
            }
            $insert = $query->execute();
            return $insert ? $this->db->lastInsertId() : false;
        } else {
            return false;
        }
    }

    //Select From Database
    public function getRows($table, $conditions = array())
    {
        $sql = 'SELECT ';
        $sql .= array_key_exists("select", $conditions) ? $conditions['select'] : '*';
        $sql .= ' FROM ' . $table;
        if (array_key_exists("join", $conditions)) {
            $sql .= ' INNER JOIN ' . $conditions['join'];
        }
        if (array_key_exists("leftjoin", $conditions)) {
            $sql .= ' LEFT JOIN ' . $conditions['leftjoin'];
        }
        if (array_key_exists("joinx", $conditions)) {
            $sql .= ' INNER JOIN ';
            $i = 0;
            foreach ($conditions['joinx'] as $key => $value) {
                $pre = ($i > 0) ? ' INNER JOIN ' : '';
                $sql .= $pre . $key  . $value;
                $i++;
            }
        }
        if (array_key_exists("joinl", $conditions)) {
            $sql .= ' LEFT JOIN ';
            $i = 0;
            foreach ($conditions['joinl'] as $key => $value) {
                $pre = ($i > 0) ? ' LEFT JOIN ' : '';
                $sql .= $pre . $key  . $value;
                $i++;
            }
        }
        if (array_key_exists("where", $conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " = '" . $value . "'";
                $i++;
            }
        }
        if (array_key_exists("wherenot", $conditions)) {
            $sql .= ' AND ';
            $i = 0;
            foreach ($conditions['wherenot'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " != '" . $value . "'";
                $i++;
            }
        }

        if (array_key_exists("where_greater_equals", $conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where_greater_equals'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " >= '" . $value . "'";
                $i++;
            }
        }

        if (array_key_exists("where_lesser_equals", $conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where_lesser_equals'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " <= '" . $value . "'";
                $i++;
            }
        }

        if (array_key_exists("where_lesser", $conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where_lesser'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " < '" . $value . "'";
                $i++;
            }
        }

        if (array_key_exists("where_greater", $conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where_greater'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " > '" . $value . "'";
                $i++;
            }
        }



        if (array_key_exists("order_by", $conditions)) {
            $sql .= ' ORDER BY ' . $conditions['order_by'];
        }
        if (array_key_exists("group_by", $conditions)) {
            $sql .= ' GROUP BY ' . $conditions['group_by'];
        }


        if (array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
            $sql .= ' LIMIT ' . $conditions['start'] . ',' . $conditions['limit'];
        } elseif (!array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
            $sql .= ' LIMIT ' . $conditions['limit'];
        }

        $query = $this->db->prepare($sql);
        $query->execute();

        if (array_key_exists("return_type", $conditions) && $conditions['return_type'] != 'all') {
            switch ($conditions['return_type']) {
                case 'count':
                    $data = $query->rowCount();
                    break;
                case 'single':
                    $data = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $data = '';
            }
        } else {
            if ($query->rowCount() > 0) {
                $data = $query->fetchAll();
            }
        }
        return !empty($data) ? $data : false;
    }

    //Update Function
    public function upDate($table, $data, $conditions)
    {
        if (!empty($data) && is_array($data)) {
            $colvalSet = '';
            $whereSql = '';
            $i = 0;

            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $colvalSet .= $pre . $key . "='" . $val . "'";
                $i++;
            }
            if (!empty($conditions) && is_array($conditions)) {
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach ($conditions as $key => $value) {
                    $pre = ($i > 0) ? ' AND ' : '';
                    $whereSql .= $pre . $key . " = '" . $value . "'";
                    $i++;
                }
            }
            $sql = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
            $query = $this->db->prepare($sql);
            $update = $query->execute();
            return $update ? $query->rowCount() : false;
        } else {
            return false;
        }
    }

    //Delete Function 
    public function delete($table, $conditions)
    {
        $whereSql = '';
        if (!empty($conditions) && is_array($conditions)) {
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach ($conditions as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $whereSql .= $pre . $key . " = '" . $value . "'";
                $i++;
            }
        }
        $sql = "DELETE FROM " . $table . $whereSql;
        $delete = $this->db->exec($sql);
        return $delete ? $delete : false;
    }
}
