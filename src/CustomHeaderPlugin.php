<?php

namespace CustomHeader;

use App\Classes\Plugin;
use App\Facades\Hook;
use CustomHeader\Pages\CustomHeaderPageV3;
use CustomHeader\Pages\CustomHeaderPage;
use Filament\Forms\Form;
use Filament\Panel;
use Filament\Schemas\Schema;
use RuntimeException;

class CustomHeaderPlugin extends Plugin
{
	public function boot()
	{
		$headerContent = $this->getSetting('header_content');
		$footerContent = $this->getSetting('footer_content');

		if ($headerContent) {
			Hook::add('Frontend::Views::Head', function ($hookName, &$output) use ($headerContent) {
				$output .= $headerContent;
			});
		}

		if ($footerContent) {
			Hook::add('Frontend::Views::Footer', function ($hookName, &$output) use ($footerContent) {
				$output .= $footerContent;
			});
		}
	}

	public function onPanel(Panel $panel): void
	{
		$panel->pages([
			$this->resolvePageClass(),
		]);
	}

	public function getPluginPage(): ?string
	{
		try {
			$page = $this->resolvePageClass();

			return $page::getUrl();
		} catch (\Throwable $th) {
			return null;
		}
	}

	/**
	 * @return class-string<\Filament\Pages\Page>
	 */
	public function resolvePageClass(): string
	{
		if (class_exists(Schema::class)) {
			return CustomHeaderPage::class;
		}

		if (class_exists(Form::class)) {
			return CustomHeaderPageV3::class;
		}

		throw new RuntimeException('Custom Header requires Filament 3 or Filament 5.');
	}
}
