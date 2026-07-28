<?php

namespace CustomHeader\Pages\Concerns;

use App\Facades\Plugin;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use RuntimeException;
use Throwable;

trait ManagesCustomHeader
{
	public ?array $data = [];

	public function mount(): void
	{
		$plugin = Plugin::getPlugin('CustomHeader');

		$this->form->fill([
			'header_content' => $plugin?->getSetting('header_content'),
			'footer_content' => $plugin?->getSetting('footer_content'),
		]);
	}

	/**
	 * @return array<string>
	 */
	public function getBreadcrumbs(): array
	{
		return [];
	}

	public function getSubheading(): string | Htmlable | null
	{
		return new HtmlString(<<<HTML
			<span class="text-sm text-gray-500">This plugin allows you to add custom headers to the website. This can be used, for example, to add metadata, javascript, css, etc.</span>
		HTML);
	}

	/**
	 * @return array<Textarea>
	 */
	protected function getCustomHeaderFields(): array
	{
		return [
			Textarea::make('header_content')
				->label('Header Content')
				->rows(5)
				->autosize(),
			Textarea::make('footer_content')
				->label('Footer Content')
				->rows(5)
				->autosize(),
		];
	}

	public function submit(): void
	{
		try {
			$plugin = Plugin::getPlugin('CustomHeader');

			if (! $plugin) {
				throw new RuntimeException('Custom Header plugin configuration is not available.');
			}

			$data = $this->form->getState();

			$plugin->updateSetting('header_content', $data['header_content'] ?? null);
			$plugin->updateSetting('footer_content', $data['footer_content'] ?? null);

			Notification::make()
				->success()
				->title(__('general.saved'))
				->send();
		} catch (Throwable $th) {
			Notification::make()
				->danger()
				->title(__('general.error'))
				->body(__('general.there_was_error_please_contact_administrator'))
				->send();

			Log::error($th);
		}
	}
}
