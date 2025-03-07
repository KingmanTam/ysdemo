<?php

class SolrClient
{
    private int $rows=12;
    private int $start=1;
    private string $wt='json';
    private string $q='';
    private string $sort='';
    private string $url = '';
    private int $total = 0;
    public function __construct($option)
    {
        $this->url = 'http://'.$option['host'].':'.$option['port'].'/solr/'.$option['core'].'/select?indent=true';
    }

    public function addField($key,$value,$accurate =false,$logic=' and '): void
    {
        if (empty($value)){
            return;
        }
        if ($accurate){
            $value = '"'.$value.'"';
        }
        if (empty($this->option['q'])){
            $this->q = '&q='.$key.':'.urlencode($value);
        }else{
            $this->q .= $logic.$key.':'.urlencode($value);
        }
    }
    public function setSort($p): void
    {
        $this->sort=$p;
    }
    public function setStart($p): void
    {
        $this->start=$p;
    }
    public function setRows($p): void
    {
        $this->rows=$p;
    }
    public function getTotal(): string
    {
        return $this->total;
    }



    public function query(){
        if (empty($this->q)){
            $this->url.='&q='.urlencode('*:*');
        }else{
            $this->url.=$this->q;
        }
        if (!empty($this->sort)){
            $this->url .='&sort='.urlencode($this->sort);
        }
        $this->url.='&wt='.$this->wt;
        $this->url.='&start='.($this->start-1)*$this->rows;
        $this->url .='&rows='.$this->rows;
        $result = file_get_contents($this->url);
        $json_data = json_decode($result, true);
        $docs = $json_data['response']['docs'];
        $this->total = $json_data['response']['numFound'];
        return $docs;
    }
}