<?php

use Phalcon\Paginator\Adapter\QueryBuilder as PAdapter;


class HotelsController extends ViewsController
{
    public function indexAction(){}

    public function showGridAction()
    {
        $builder = $this->modelsManager->createBuilder()
            ->from('Hotels')
            ->orderBy('name ASC');

        if($selectedCityId = $this->request->get('city')) $builder->where('city_id='.$selectedCityId);

        $paginator = new PAdapter(
            array(
                "builder" => $builder,
                "limit"=> 25,
                "page" => $this->request->get('page')
            )
        );

//        echo $builder->getPhql();
//        die;

        $grid = $paginator->getPaginate();

        $this->view->setVar('grid', $grid);

        // get cities
        $builder = $this->modelsManager->createBuilder()
            ->from('Hotels')
            ->innerJoin('Cities', 'Cities.city_id = Hotels.city_id')
            ->columns(array('Cities.city_id', 'Cities.title_ru'))
            ->where('Cities.http_status = 202')
            ->orderBy('Cities.title_ru ASC')
            ->groupBy('Cities.city_id');

        $cities = $builder->getQuery()->execute();

        $this->tag->setDefault('city', $this->request->get('city'));

        $this->view->setVar('cities', $cities);
    }

    public function showItemAction($id)
    {
        try{
            if(empty($id)) throw new \Exception;

            $marketplace = new Marketplace();

            $row = $marketplace->query()->where('id='.$id)->limit(1)->execute()->getFirst();

            if(!count($row)) throw new \Exception;

            $this->setTitle($row->getName());

            $this->view->setVar('row', $row);

        } catch (\Exception $e){
            $this->e404();
        }
    }
}

