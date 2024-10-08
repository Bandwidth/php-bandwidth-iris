<?php

/**
 * @model Report
 * https://api.test.inetwork.com/v1.0/accounts/reports
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

use AllowDynamicProperties;

require_once("ReportsInstanceModel.php");

#[AllowDynamicProperties]
final class Reports extends RestEntry {

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function getList($filters = array()) {

        $reports = [];

        $data = parent::_get('reports', $filters, array("page"=> 1, "size" => 30), array("page", "size"));
        if($data['Reports']) {
            foreach($data['Reports']['Report'] as $report) {
                $reports[] = new Report($this, $report);
            }
        }

        return $reports;
    }

    public function get_by_id($id) {
        $order = new Report($this, array("Id" => $id));
        $order->get();
        return $order;
    }

    public function get_appendix() {
        return '/reports';
    }
}


#[AllowDynamicProperties]
final class ReportValue {
    use BaseModel;

    protected $fields = array(
        "InternalName" => array("type" => "string"),
        "DisplayName" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}


#[AllowDynamicProperties]
final class ReportValues {
    use BaseModel;

    protected $fields = array(
        "Value" => array("type" => "\Iris\ReportValue")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}


#[AllowDynamicProperties]
final class ReportParameter {
    use BaseModel;

    protected $fields = array(
        "Name" => array("type" => "string"),
        "Type" => array("type" => "string"),
        "Required" => array("type" => "string"),
        "Description" => array("type" => "string"),
        "MultiSelectAllowed" => array("type" => "string"),
        "HelpInformation" => array("type" => "string"),
        "Values" => array("type" => "\Iris\ReportValues"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}


#[AllowDynamicProperties]
final class ReportParameters {
    use BaseModel;

    protected $fields = array(
        "Parameter" => array("type" => "\Iris\ReportParameter")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}


#[AllowDynamicProperties]
final class Report extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "Id" => array("type" => "string"),
        "Name" => array("type" => "string"),
        "Description" => array("type" => "string"),
        "Parameters" => array("type" => "\Iris\ReportParameters"),
    );

    public function __construct($reports, $data)
    {
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($reports)) {
            $this->parent = $reports;
            parent::_init($reports->get_rest_client(), $reports->get_relative_namespace());
        }
    }

    public function get() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');

        $data = parent::_get($this->id);
        $this->set_data($data['Report']);
    }

    public function areaCodes()
    {
        $url = sprintf('%s/%s', $this->id, 'areaCodes');
        $data = parent::_get($url);
        return $data;
    }

    public function instances($filters = array())
    {
        $rep_instances = [];

        $data = parent::_get($this->Id.'/instances', $filters, array("page"=> 1, "size" => 30), array("page", "size"));

        if($data['Instances']) {
            foreach($data['Instances']['Instance'] as $instance) {
                $rep_instances[] = new ReportInstance($this, $instance);
            }
        }

        return $rep_instances;
    }

    public function modifyInstance($instance) {
        $response = parent::post($this->Id."/instances/".$instance->Id, "Instance", $this->to_array());
        return $response; //this api endpoint returns a response header
    }

    public function get_appendix() {
        return $this->parent->get_appendix();
    }
}
