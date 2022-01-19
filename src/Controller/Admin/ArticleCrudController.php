<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CategoryRepository;
class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
           
            AssociationField::new('category'),
            TextField::new('title'),
            NumberField::new('price'),
        ];
    }

}
