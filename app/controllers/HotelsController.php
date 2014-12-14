<?php

use Phalcon\Paginator\Adapter\QueryBuilder as PAdapter;


class HotelsController extends ViewsController
{
    public function indexAction(){}

    public function showGridAction()
    {
        $builder = $this->modelsManager->createBuilder()
            ->from('Hotels')
            ->where('city_id=1')
            ->orderBy('name ASC');

        $paginator = new PAdapter(
            array(
                "builder" => $builder,
                "limit"=> 20,
                "page" => $this->request->get('page')
            )
        );

//        echo $builder->getPhql();
//        die;

        $grid = $paginator->getPaginate();

        $this->view->setVar('grid', $grid);
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

