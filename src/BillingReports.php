<?php

/**
 * @model BillingReports
 *
 */

namespace Iris;

class BillingReports extends RestEntry
{
    public function __construct($parent)
    {
        $this->parent = $parent;
        parent::_init(
            $this->parent->get_rest_client(),
            $this->parent->get_relative_namespace()
        );
    }

    public function getList($filters = [])
    {
        $billingReports = [];

        $data = parent::_get('billingreports');

        if (!(isset($data['BillingReportList']['BillingReport']) && is_array($data['BillingReportList']['BillingReport'])))
        {
            return $billingReports;
        }

        foreach ($data['BillingReportList']['BillingReport'] as $item)
        {
            if (isset($item['BillingReportId']))
            {
                $item['Id'] = $item['BillingReportId'];
                unset($item['BillingReportId']);
            }
            if (isset($item['BillingReportKind']))
            {
                $item['Type'] = $item['BillingReportKind'];
                unset($item['BillingReportKind']);
            }
            $billingReports[] = new BillingReport($this, $item);
        }

        return $billingReports;
    }

    public function billingreport($id)
    {
        $billingReport = new BillingReport($this, ["Id" => $id]);
        $billingReport->get();

        return $billingReport;
    }

    public function get_appendix()
    {
        return '/billingreports';
    }

    public function request($data)
    {
        $billingReport = new BillingReport($this, $data);

        return $billingReport->save();
    }
}

class BillingReport extends RestEntry
{
    use BaseModel;

    protected $fields = array(
        "Id" => array("type" => "string"),
        "UserId" => array("type" => "string"),
        "Type" => array("type" => "string"),
        "DateRange" => array("type" => "\Iris\DateRange"),
        "ReportStatus" => array("type" => "string"),
        "CreatedDate" => array("type" => "string"),
        "Description" => array("type" => "string"),
    );

    public function __construct($parent, $data)
    {
        $this->set_data($data);
        $this->parent = $parent;
        parent::_init(
            $parent->get_rest_client(),
            $parent->get_relative_namespace()
        );
    }

    public function get()
    {
        $data = parent::_get($this->get_id());
        $this->set_data($data);
    }
    
    public function get_id()
    {
        if (!isset($this->Id))
        {
            throw new \Exception('Id should be provided');
        }

        return $this->Id;
    }

    public function save()
    {
        $header = parent::post(null, "BillingReport", $this->to_array());
        $splitted = explode("/", $header['Location']);
        $this->Id = end($splitted);

        return $this;
    }

    /**
     * Download zip report file
     *
     * @return mixed
     * @throws \Exception
     */
    public function file()
    {
        if (!isset($this->ReportStatus) || $this->ReportStatus !== 'COMPLETED')
        {
            return false;
        }

        $url = sprintf('%s/%s', $this->get_id(), 'file');
        $url = parent::get_url($url);

        return $this->client->get($url, $options);
    }
}
