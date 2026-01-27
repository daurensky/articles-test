<?php

namespace App\Infrastructure\Http;

use Exception;
use Smarty\Smarty;
use Smarty\Exception as SmartyException;

abstract class AbstractController
{
    protected Smarty $smarty;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->smarty = new Smarty();

        $this->smarty->setTemplateDir(ROOT_PATH . '/resources/templates');
        $this->smarty->setCompileDir(ROOT_PATH . '/storage/cache/templates_c');
        $this->smarty->setCacheDir(ROOT_PATH . '/storage/cache/caches');

        // TODO: Можно вынести в отдельные классы, но для упрощения реализовал прямо в контроллере
        if (getenv('APP_ENV') === 'development') {
            $this->smarty->assign('cssPath', 'http://localhost:5173/resources/scss/main.scss');
        } else {
            $manifestPath = ROOT_PATH . '/public/build/.vite/manifest.json';

            if (!file_exists($manifestPath)) {
                throw new Exception('Manifest file not found');
            }

            $manifest = json_decode(file_get_contents($manifestPath), true);

            $cssFile = '/build/' . $manifest['resources/scss/main.scss']['file'];
            $this->smarty->assign('cssPath', $cssFile);
        }
    }

    /**
     * @throws SmartyException
     */
    protected function render(string $template, array $vars = []): void
    {
        foreach ($vars as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template);
    }
}