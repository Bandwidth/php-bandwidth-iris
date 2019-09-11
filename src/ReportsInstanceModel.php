<?php

/**
 * @model ReportInstance
 * https://api.test.inetwork.com/v1.0/accounts/reports/{id}/instances
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class InstanceParameters {
    use BaseModel;

    protected $fields = array(
        "Parameter" => array("type" => "\Iris\InstanceParameter")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

final class InstanceParameter {
    use BaseModel;

    protected $fields = array(
        "Name" => array("type" => "string"),
        "Value" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

final class ReportInstance extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "Id" => array("type" => "string"),
        "ReportId" => array("type" => "string"),
        "ReportName" => array("type" => "string"),
        "OutputFormat" => array("type" => "string"),
        "RequestedByUserName" => array("type" => "string"),
        "RequestedAt" => array("type" => "string"),
        "Parameters" => array("type" => "\Iris\InstanceParameters"),
        "Status" => array("type" => "string"),
        "ExpiresAt" => array("type" => "string"),
    );

    public function __construct($report, $data)
    {
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($report)) {
            $this->parent = $report;
            parent::_init($report->get_rest_client(), $report->get_relative_namespace());
        }
    }

    public function get() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');

        $data = parent::_get($this->id);
        $this->set_data($data['Instance']);
    }

    public function get_by_id($instanceId) {
        //TODO: Get instances/{instanceId}
    }

    public function create() {
        $response = parent::post(null, "Instance", $this->to_array());
        return $response; //this api endpoint returns a response header
    }

}
