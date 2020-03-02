<?php

    class db{
        private $url = "192.168.1.93";
        private $port = "3309";
        private $user = "receiptUser";
        private $password = 'b13fDsMSzTrx#Gh4qmNCdEwFpA8XXWxb^54oxFGDMzn!Am&jn&aA$2y28Dk*rqNnUl0V3nWz55i3&Nk73EwHmPxH2MchUaJH9dn';
        private $database = "daycareReceipts";
        private $connection;


        public function db(){
            $this->connection = new mysqli($this->url, $this->user, $this->password, $this->database, $this->port);
            if ($this->connection->errno != 0){
               echo "DB Connection Error\n";
                  
            } 
        }


        public function getItemList(){
            $query = 'select itemTypeID, itemType_Name from itemType order by itemTypeID';
            $stmt = $this->connection->prepare($query);
            $returnData=[];
            $itemID;
            $itemName;

            if($stmt){
               //echo "prepared\r\n";
                if($stmt->execute()){
                  //  echo "executed\r\n";
                    if($stmt->bind_result($itemID, $itemName)){
                      //  echo "Bind Result Worked\r\n";
                        while($stmt->fetch()){
                            $temp = array("id"=>$itemID, "itemName"=>$itemName);
                            array_push($returnData, $temp);
                            
                        }
                        return $returnData;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }

        }

        public function addReceiptItem($data){
            //var_dump($data);

            if(isset($data['date']) && 
               isset($data['store']) && 
               isset($data['name']) &&
               isset($data['cost']) &&
               isset($data['typeID']) &&
               isset($data['comments'])
            ){
                $query = "insert into receipt (receipt_Date, receipt_StoreName, receipt_ItemName, receipt_ItemCost, receipt_ItemTypeID, receipt_Comments) values" .
                         "(?, ?, ?, ?, ?, ?)";
                
                $stmt = $this->connection->prepare($query);
                if($stmt){
                    if($stmt->bind_param('sssdds', $data['date'], $data['store'], $data['name'], $data['cost'], $data['typeID'], $data['comments'])){
                        if($stmt->execute()){
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
                


            } else {
                return false;
            }



            
        }
        
        public function getItems(){

            $query = "select receipt_Date, receipt_StoreName, receipt_ItemName, receipt_itemCost, itemType_Name " .
                     "from receipt join itemType where receipt.receipt_ItemTypeID = itemType.itemTypeID " .
                     "order by receipt_Date desc, receipt_StoreName limit 25";
            $rDate;
            $rStore;
            $rName;
            $rCost;
            $rType;
            $tableData = [];

            $stmt = $this->connection->prepare($query);
            if($stmt){
                if($stmt->bind_result($rDate, $rStore, $rName, $rCost, $rType)){
                    if($stmt->execute()){
                        while($stmt->fetch()){
                            $arrayBuild = array("date"=> $rDate,
                                               "store"=>$rStore,
                                               "name"=>$rName,
                                               "cost"=>$rCost,
                                               "type"=>$rType);
                            array_push($tableData, $arrayBuild);
                        }
                        return $tableData;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }


        }

        public function getStoreTotals(){
            $query = "select receipt_Date, receipt_StoreName, sum(receipt_itemCost) as \"Total\" " .
                     "from receipt " .
                     "group by receipt_StoreName, receipt_Date " .
                     "order by receipt_Date desc";
            
            
            $rDate;
            $rStore;
            $rCost;
            $tableData = [];

            $stmt = $this->connection->prepare($query);
            if($stmt){
                if($stmt->bind_result($rDate, $rStore, $rCost)){
                    if($stmt->execute()){
                        while($stmt->fetch()){
                            $arrayBuild = array("date"=> $rDate,
                                                "store"=>$rStore,
                                                "cost"=>$rCost
                                                );
                            array_push($tableData, $arrayBuild);
                        }
                        return $tableData;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

?>