<?php

class TestContainer extends ContainerBase
{
    public function testGetTemplateVarHeadTitle()
    {
        $varExpected = 'Jewel CMS demo site';
        $themeBinding = $this->container->getService('Theme.Binding');
        $themeBinding->set('Head.Title', $varExpected);
        $varActual = $themeBinding->get('Head.Title');
        $this->assertEquals($varExpected, $varActual);
    }

    public function testThemeEngineRender()
    {
        $varExpected = '<p>Hello Ben!</p>';
        $themeEngine = $this->container->getService('Theme.EngineUT');
        $varActual = $themeEngine->render('index.html', array('Name' => 'Ben'));
        $this->assertEquals($varExpected, $varActual);
    }
} 