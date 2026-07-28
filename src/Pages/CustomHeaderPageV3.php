<?php

namespace CustomHeader\Pages;

use CustomHeader\Pages\Concerns\ManagesCustomHeader;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page as FilamentPage;

class CustomHeaderPageV3 extends FilamentPage implements HasForms
{
	use InteractsWithForms;
	use ManagesCustomHeader;

	protected static ?string $title = 'Custom Header Plugin';

	protected static string $view = 'CustomHeader::custom-header';

	protected static bool $shouldRegisterNavigation = false;

	protected static ?string $slug = 'custom-header';

	public function form(Form $form): Form
	{
		return $form
			->schema([
				Section::make()
					->schema($this->getCustomHeaderFields())
			])
			->statePath('data');
	}
}
