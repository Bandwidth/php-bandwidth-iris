<?php

namespace Iris;

class Account extends RestEntry {
    public function __construct($account_id, $client=Null, $namespace='accounts')
    {
        parent::_init($client, $namespace);
        $this->account_id = $account_id;
        $this->client = $client;
    }

    public function tnoptions() {
        if(!isset($this->tnoptions))
            $this->tnoptions = new TnOptions($this);
        return $this->tnoptions;
    }

    /**
    * @params \Iris\TnLineOptions
    */
    public function lineOptionOrders(TnLineOptions $data) {
        $url = sprintf('%s/%s', $this->account_id, 'lineOptionOrders');
        $response = parent::post($url, "LineOptionOrder", $data->to_array());
        return new TnLineOptionOrderResponse($response);
    }


    public function inserviceNumbers($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'inserviceNumbers');
        $response = parent::_get($url, $filters);
        return new TelephoneNumbers($response['TelephoneNumbers']);
    }

    public function inserviceNumbers_totals($filters = array()) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'inserviceNumbers', 'totals');
        $response = parent::_get($url, $filters);
        return $response['Count'];
    }

    public function orders() {
        if(!isset($this->orders))
            $this->orders = new Orders($this);
        return $this->orders;
    }

    public function portins() {
        if(!isset($this->portins))
            $this->portins = new Portins($this);
        return $this->portins;
    }

    public function disconnects() {
        if(!isset($this->disconnects))
            $this->disconnects = new Disconnects($this);
        return $this->disconnects;
    }

    public function disnumbers($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'discNumbers');
        $response = parent::_get($url, $filters);
        return new TelephoneNumbers($response['TelephoneNumbers']);
    }

    public function disnumbers_totals($filters = array()) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'discNumbers', 'totals');
        $response = parent::_get($url, $filters);
        return $response['Count'];
    }

    public function portouts() {
        if(!isset($this->portouts))
            $this->portouts = new Portouts($this);
        return $this->portouts;
    }

    public function lsrorders() {
        if(!isset($this->lsrorders))
            $this->lsrorders = new Lsrorders($this);
        return $this->lsrorders;
    }

    public function dldas() {
        if(!isset($this->dldas))
            $this->dldas = new Dldas($this);
        return $this->dldas;
    }

    public function subscriptions() {
        if(!isset($this->subscriptions))
            $this->subscriptions = new Subscriptions($this);
        return $this->subscriptions;
    }

    public function tnsreservations() {
        if(!isset($this->tnsreservations))
            $this->tnsreservations = new TnsReservations($this);
        return $this->tnsreservations;
    }

    public function sites() {
        if(!isset($this->sites))
            $this->sites = new Sites($this);
        return $this->sites;
    }

    public function users() {
        if(!isset($this->users))
            $this->users = new Users($this);
        return $this->users;
    }

    /**
     * Get BillingReports instance
     *
     * @return BillingReports
     */
    public function billingreports()
    {
        if (!isset($this->billingReports))
        {
            $this->billingReports = new BillingReports($this);
        }

        return $this->billingReports;
    }

    public function reports() {
        if(!isset($this->reports))
            $this->reports = new Reports($this);
        return $this->reports;
    }

    public function lidbs() {
        if(!isset($this->lidbs))
            $this->lidbs = new Lidbs($this);
        return $this->lidbs;
    }

    /**
     * Account Info by Id
     *
     */
    public function get($url, $options=Array(), $defaults = Array(), $required = Array())
    {
        $data = parent::_get($this->account_id);
        return $data;
    }

    private function parse_response($data, $level1, $level2, $count, $classname) {
        $out = [];
        $items = $level2 ? $data[$level1][$level2]: $data[$level1];

        if($count == 1 || $this->is_assoc($items))
            $items = [ $items ];

        foreach($items as $item) {
            $out[] = new $classname($item);
        }

        return $out;
    }

    public function availableNpaNxx($filters=Array()) {
        $url = sprintf('%s/%s', $this->account_id, 'availableNpaNxx');
        $data = parent::_get($url, $filters);
        $out = [];

        if($data['AvailableNpaNxxList']) {
            $items =  $data['AvailableNpaNxxList']['AvailableNpaNxx'];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $avNpaNxx) {
                $out[] = new \Iris\AvailableNpaNxx($avNpaNxx);
            }
        }

        return $out;
    }

    public function availableNumbers($filters=Array()){
        $query_fields = ["areaCode", "quantity", "enableTNDetail", "localVanity", "npaNxx", "npaNxxx",
            "LCA", "enableTNDetail", "rateCenter", "state", "quantity", "tollFreeVanity",
            "tollFreeWildCardPattern", "city", "zip", "lata" ];

        foreach($filters as $field => $value) {
            if(!in_array($field, $query_fields))
                throw new \Exception("Field $field is not allowed.");
        }

        $url = sprintf('%s/%s', $this->account_id, 'availableNumbers');
        $data = parent::_get($url, $filters);
        $count = isset($data['ResultCount']) ? $data['ResultCount'] : 0;

        $types = [
            ["level1" => "TelephoneNumberDetailList", "level2" => "TelephoneNumberDetail", "classname" => "\Iris\TelephoneNumberDetail"],
            ["level1" => "TelephoneNumberList", "level2" => false, "classname" => "\Iris\TelephoneNumbers"],
        ];

        foreach($types as $type) {
            if(isset($data[$type['level1']]) && (!$type['level2'] || isset($data[$type['level1']][$type['level2']])))
                return $this->parse_response($data, $type['level1'], $type['level2'], $count, $type['classname']);
        }
    }

    public function lnpChecker($array, $fullcheck = false) {
        $data = ["TnList" => ["Tn" => $array ]];
        $obj = new \Iris\NumberPortabilityRequest($data);
        if($fullcheck !== false && in_array($fullcheck, ["true", "false", "onnetportability", "offnetportability"])) {
            $f = "?fullCheck=$fullcheck";
        } else {
            $f = "";
        }

        $url = sprintf('%s/%s%s', $this->account_id, 'lnpchecker', $f);
        $res = parent::post($url, "NumberPortabilityRequest", $obj->to_array());
        return new NumberPortabilityResponse($res);
    }

    public function serviceNumbers($filters=Array()){
        $url = sprintf('%s/%s', $this->account_id, 'serviceNumbers');
        $data = parent::_get($url, $filters);
        return $data;
    }

    public function products($filters=Array()){
        $url = sprintf('%s/%s', $this->account_id, 'products');
        $data = parent::_get($url, $filters);
        return $data;
    }

    public function bdrs(Bdr $bdr) {
        $url = sprintf('%s/%s', $this->account_id, 'bdrs');
        $data = parent::post($url, 'Bdr', $bdr->to_array());
        return new BdrCreationResponse($data);
    }
    public function bdr($id) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'bdrs', $id);
        $data = parent::post($url, 'Bdr', $bdr->to_array());
        return new BdrCreationResponse($data);
    }


    public function get_relative_namespace() {
      return "accounts/{$this->account_id}";
    }

    public function get_rest_client() {
      return $this->client;
    }

    public function getImportTnOrders($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'importTnOrders');
        $response = parent::_get($url, $filters);
        return new ImportTnOrderResponse($response);
    }

    public function createImportTnOrder(ImportTnOrder $order) {
        $url = sprintf('%s/%s', $this->account_id, 'importTnOrders');
        $data = parent::post($url, 'ImportTnOrder', $order->to_array());
        return new ImportTnOrderResponse($data);
    }

    public function getImportTnOrder($id) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'importTnOrders', $id);
        $response = parent::_get($url);
        return new ImportTnOrder($response);
    }

    public function getImportTnOrderHistory($id) {
        $url = sprintf('%s/%s/%s/%s', $this->account_id, 'importTnOrders', $id, 'history');
        $response = parent::_get($url);
        return new OrderHistoryResponse($response);
    }

    public function getImportTnOrderLoas($order_id) {
        $url = sprintf('%s/%s/%s/%s', $this->account_id, 'importtnorders', $order_id, 'loas');
        $response = parent::_get($url);
        return new FileListResponse($response);
    }

    public function uploadImportTnOrderLoaFile($order_id, $file_contents, $mime_type) {
        $url = sprintf('%s/%s/%s/%s', $this->account_id, 'importtnorders', $order_id, 'loas');
        parent::raw_file_post($url, $file_contents, array("Content-Type" => $mime_type));
    }

    public function downloadImportTnOrderLoaFile($order_id, $file_id) {
        $url = sprintf('accounts/%s/%s/%s/%s/%s', $this->account_id, 'importtnorders', $order_id, 'loas', $file_id);
        //using the request function directly in order to set $parse=false
        $response = $this->client->request('get', $url, $options=[], $parse=false)->getBody()->getContents();
        return $response;
    }

    public function replaceImportTnOrderLoaFile($order_id, $file_id, $file_contents, $mime_type) {
        $url = sprintf('%s/%s/%s/%s/%s', $this->account_id, 'importtnorders', $order_id, 'loas', $file_id);
        parent::raw_file_put($url, $file_contents, array("Content-Type" => $mime_type));
    }

    public function deleteImportTnOrderLoaFile($order_id, $file_id) {
        $url = sprintf('%s/%s/%s/%s/%s', $this->account_id, 'importtnorders', $order_id, 'loas', $file_id);
        parent::_delete($url);
    }

    public function getImportTnOrderLoaFileMetadata($order_id, $file_id) {
        $url = sprintf('%s/%s/%s/%s/%s/%s', $this->account_id, 'importtnorders', $order_id, 'loas', $file_id, 'metadata');
        $response = parent::_get($url);
        return new FileMetaData($response);
    }

    public function updateImportTnOrderLoaFileMetadata($order_id, $file_id, FileMetaData $file_metadata) {
        $url = sprintf('%s/%s/%s/%s/%s/%s', $this->account_id, 'importtnorders', $order_id, 'loas', $file_id, 'metadata');
        parent::put($url, 'FileMetaData', $file_metadata->to_array());
    }

    public function deleteImportTnOrderLoaFileMetadata($order_id, $file_id) {
        $url = sprintf('%s/%s/%s/%s/%s/%s', $this->account_id, 'importtnorders', $order_id, 'loas', $file_id, 'metadata');
        parent::_delete($url);
    }

    public function checkTnsPortability($tns) {
        $url = sprintf('%s/%s', $this->account_id, 'importTnChecker');
        $payload = new ImportTnCheckerPayload(array(
            "TelephoneNumbers" => array(
                "TelephoneNumber" => $tns
        )));
        $data = parent::post($url, 'ImportTnCheckerPayload', $payload->to_array());
        return new ImportTnCheckerResponse($data);
    }

    public function getInserviceNumbers($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'inserviceNumbers');
        $response = parent::_get($url, $filters);
        return new InserviceTns($response);
    }

    public function checkInserviceNumber($tn) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'inserviceNumbers', $tn);
        $response = parent::_get($url);
        return $response;
    }

    public function getRemoveImportedTnOrders($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'removeImportedTnOrders');
        $response = parent::_get($url, $filters);
        return new RemoveImportedTnOrderSummaryResponse($response);
    }

    public function createRemoveImportedTnOrder(RemoveImportedTnOrder $order) {
        $url = sprintf('%s/%s', $this->account_id, 'removeImportedTnOrders');
        $data = parent::post($url, 'RemoveImportedTnOrder', $order->to_array());
        return new RemoveImportedTnOrderResponse($data);
    }

    public function getRemoveImportedTnOrder($id) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'removeImportedTnOrders', $id);
        $response = parent::_get($url);
        return new RemoveImportedTnOrder($response);
    }

    public function getRemoveImportedTnOrderHistory($id) {
        $url = sprintf('%s/%s/%s/%s', $this->account_id, 'removeImportedTnOrders', $id, 'history');
        $response = parent::_get($url);
        return new OrderHistoryResponse($response);
    }

    public function createCsrOrder(Csr $order) {
        $url = sprintf('%s/%s', $this->account_id, 'csrs');
        $data = parent::post($url, 'Csr', $order->to_array());
        return new CsrResponse($data);  
    }

    public function getCsrOrder($id) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'csrs', $id);
        $response = parent::_get($url);
        return new CsrResponse($response);
    }

    public function replaceCsrOrder($id, Csr $order) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'csrs', $id);
        $response = parent::put($url, 'Csr', $order->to_array());
        return new CsrResponse($response);
    }

    public function getCsrOrderNotes($id) {
        $url = sprintf('%s/%s/%s/%s', $this->account_id, 'csrs', $id, 'notes');
        $response = parent::_get($url);
        return new CsrNotesList($response);
    }

    public function addNoteToCsr($id, CsrNote $note) {
        $url = sprintf('%s/%s/%s/%s', $this->account_id, 'csrs', $id, 'notes');
        $data = parent::post($url, 'Note', $note->to_array());
        return $data;
    }

    public function updateCsrOrderNote($orderId, $noteId, CsrNote $note) {
        $url = sprintf('%s/%s/%s/%s/%s', $this->account_id, 'csrs', $orderId, 'notes', $noteId);
        $data = parent::put($url, 'Note', $note->to_array());
        return $data;
    }

    public function getAlternateEndUserInformation($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'aeuis');
        $data = parent::_get($url, $filters);
        return $data;
    }

    public function getAlternateCallerInformation($acid) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'aeuis', $acid);
        $data = parent::_get($url);
        return $data;
    }

    public function createEmergencyNotificationEndpointOrder($order) {
        $url = sprintf('%s/%s', $this->account_id, 'emergencyNotificationEndpointOrders');
        $data = parent::post($url, 'EmergencyNotificationEndpointOrder', $order);
        return $data;
    }

    public function getEmergencyNotificationEndpointOrders($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'emergencyNotificationEndpointOrders');
        $data = parent::_get($url, $filters);
        return $data;
    }

    public function getEmergencyNotificationEndpointOrder($id) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'emergencyNotificationEndpointOrders', $id);
        $data = parent::_get($url);
        return $data;
    }

    public function createEmergencyNotificationGroupOrder($order) {
        $url = sprintf('%s/%s', $this->account_id, 'emergencyNotificationGroupOrders');
        $data = parent::post($url, 'EmergencyNotificationGroupOrder', $order);
        return $data;
    }

    public function getEmergencyNotificationGroupOrders($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'emergencyNotificationGroupOrders');
        $data = parent::_get($url, $filters);
        return $data;
    }

    public function getEmergencyNotificationGroupOrder($id) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'emergencyNotificationGroupOrders', $id);
        $data = parent::_get($url);
        return $data;
    }

    public function getEmergencyNotificationGroups($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'emergencyNotificationGroups');
        $data = parent::_get($url, $filters);
        return $data;
    }

    public function getEmergencyNotificationGroup($id) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'emergencyNotificationGroups', $id);
        $data = parent::_get($url);
        return $data;
    }

    public function createEmergencyNotificationRecipient($recipient) {
        $url = sprintf('%s/%s', $this->account_id, 'emergencyNotificationRecipients');
        $data = parent::post($url, 'EmergencyNotificationRecipient', $recipient);
        return $data;
    }

    public function getEmergencyNotificationRecipients($filters = array()) {
        $url = sprintf('%s/%s', $this->account_id, 'emergencyNotificationRecipients');
        $data = parent::_get($url, $filters);
        $return $data;
    }

    public function getEmergencyNotificationRecipient($id) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'emergencyNotificationRecipients', $id);
        $data = parent::_get($url);
        return $data;
    }

    public function replaceEmergencyNotificationRecipient($id, $recipient) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'emergencyNotificationRecipients', $id);
        $data = parent::put($url, 'EmergencyNotificationRecipient', $recipient);
        return $data;
    }

    public function deleteEmergencyNotificationRecipient($id) {
        $url = sprintf('%s/%s/%s', $this->account_id, 'emergencyNotificationRecipients', $id);
        $data = parent::_delete($url);
        return $data;
    }
}
