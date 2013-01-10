<?php

namespace Unik\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MenuBuilder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $this->getMenu('main', $factory);

        $menu->setChildrenAttribute('class', 'sf-menu');
        $this->setDropdownMenuAttributes($menu);
        foreach ($menu as $row) {
            if ($row->hasChildren()) {
                $row->setLabel($row->getName().' <b class="caret"></b>');
            }
        }

        return $menu;
    }

    public function footerLeftMenu(FactoryInterface $factory, array $options)
    {
        $menu = $this->getMenu('footer_left', $factory);

        $menu->setChildrenAttribute('class', 'ft-menu');

        return $menu;
    }

    public function footerRightMenu(FactoryInterface $factory, array $options)
    {
        $menu = $this->getMenu('footer_right', $factory);

        $menu->setChildrenAttribute('class', 'ft-menu');

        return $menu;
    }

    protected function getMenu($name, $factory)
    {
        $root = $this->container->get('msi_cmf.menu_root_manager')->findRootByName($name);

        if (!$root) {
            throw new NotFoundHttpException();
        }

        $menu = $factory->createFromNode($root);
        if (!$menu->getExtra('published')) {
            foreach ($menu->getChildren() as $child) {
                $menu->removeChild($child);
            }
        }
        $this->removeUnpublished($menu);

        return $menu;
    }

    protected function setDropdownMenuAttributes($menuItem)
    {
        foreach ($menuItem->getChildren() as $child) {
            if ($child->hasChildren()) {
                $child->setAttribute('class', 'dropdown');
                $child->setLinkAttribute('class', 'dropdown-toggle');
                $child->setLinkAttribute('data-toggle', 'dropdown');
                $child->setChildrenAttribute('class', 'dropdown-menu');
            }
            $this->setDropdownSubmenuAttributes($child);
        }
    }

    protected function setDropdownSubmenuAttributes($menuItem)
    {
        foreach ($menuItem->getChildren() as $child) {
            if ($child->hasChildren()) {
                $child->setAttribute('class', 'dropdown-submenu');
                $child->setChildrenAttribute('class', 'dropdown-menu');
                $child->setLinkAttribute('tabindex', -1);
            }
        }
    }

    protected function findCurrent($node)
    {
        $requestUri = $this->container->get('request')->getRequestUri();
        if ($pos = strrpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        foreach ($node->getChildren() as $child) {
            $menuUri = $child->getUri();
            if ($menuUri === $requestUri) {
                $child->setCurrent(true);
                $this->sidebarMenu = $child->getParent();
            } else {
                $child->setCurrent(false);
                $this->findCurrent($child);
            }
        }
    }

    public function removeUnpublished($node)
    {
        foreach ($node->getChildren() as $child) {
            $child->setExtra('safe_label', true);
            if ($child->hasChildren()) {
                $this->removeUnpublished($child);
            }
            if (!$child->getExtra('published')) {
                $child->getParent()->removeChild($child);
            }
        }
    }
}
