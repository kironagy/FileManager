<?php

namespace Botble\Cloudify\Providers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Cloudify\Http\Middleware\RedirectToSettingIfCurrentPageIsSystem;
use Botble\Cloudify\PanelSections\CloudifyPanelSection;
use Botble\Dashboard\Events\RenderingDashboardWidgets;
use Botble\Dashboard\Supports\DashboardWidgetInstance;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Sanctum\Sanctum;

class CloudifyServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('packages/cloudify')
            ->loadMigrations()
            ->loadAndPublishConfigurations(['cloudify', 'permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes(['web', 'api'])
            ->registerPanelSections()
            ->loadAndPublishViews()
            ->registerDashboardWidgets();

        if (class_exists(Sanctum::class)) {
            Sanctum::ignoreMigrations();
        }

        if ($mediaSizes = $this->app['config']->get('packages.cloudify.cloudify.media_sizes')) {
            $mediaSizes = explode(',', $mediaSizes);

            foreach ($mediaSizes as $key => $mediaSize) {
                $mediaSize = explode('x', $mediaSize);

                if (count($mediaSize) < 2) {
                    continue;
                }

                RvMedia::addSize('size_' . ($key + 1), $mediaSize[0], $mediaSize[1]);
            }
        }

        if ($mediaAllowedExtensions = $this->app['config']->get('packages.cloudify.cloudify.media_allowed_extensions')) {
            $this->app['config']->set('core.media.media.allowed_mime_types', $mediaAllowedExtensions);
        }

        $this->app['events']->listen(RouteMatched::class, function () {
            $this->app[Router::class]->pushMiddlewareToGroup('web', RedirectToSettingIfCurrentPageIsSystem::class);
        });

        $this->configRateLimiterForApi();

        DashboardMenu::beforeRetrieving(function () {
            DashboardMenu::removeItem('cms-core-system')
                ->removeItem('cms-core-tools');
        });
    }

    protected function registerPanelSections(): static
    {
        PanelSectionManager::default()
            ->beforeRendering(function () {
                PanelSectionManager::register(CloudifyPanelSection::class);
            })
            ->moveGroup('system', 'settings');

        return $this;
    }

    protected function registerDashboardWidgets(): static
    {
        $this->app['events']->listen(RenderingDashboardWidgets::class, function () {
            add_filter(DASHBOARD_FILTER_ADMIN_LIST, function (array $widgets, Collection $widgetSettings) {
                return (new DashboardWidgetInstance())
                    ->setType('stats')
                    ->setPermission('media.index')
                    ->setTitle(trans('packages/cloudify::cloudify.widget.total_media_folders'))
                    ->setKey('widget-total-media-folders')
                    ->setIcon('ti ti-box')
                    ->setColor('info')
                    ->setStatsTotal(MediaFolder::query()->count())
                    ->setRoute(route('media.index'))
                    ->setColumn('col-12 col-md-6 col-lg-3')
                    ->setPriority(800)
                    ->init($widgets, $widgetSettings);
            }, 800, 2);

            add_filter(DASHBOARD_FILTER_ADMIN_LIST, function (array $widgets, Collection $widgetSettings) {
                return (new DashboardWidgetInstance())
                    ->setType('stats')
                    ->setPermission('media.index')
                    ->setTitle(trans('packages/cloudify::cloudify.widget.total_media_files'))
                    ->setKey('widget-total-media-files')
                    ->setIcon('ti ti-file')
                    ->setColor('info')
                    ->setStatsTotal(MediaFile::query()->count())
                    ->setRoute(route('media.index'))
                    ->setColumn('col-12 col-md-6 col-lg-3')
                    ->setPriority(800)
                    ->init($widgets, $widgetSettings);
            }, 820, 2);

            add_filter(DASHBOARD_FILTER_ADMIN_LIST, function (array $widgets, Collection $widgetSettings) {
                return (new DashboardWidgetInstance())
                    ->setType('stats')
                    ->setPermission('media.index')
                    ->setTitle(trans('packages/cloudify::cloudify.widget.total_media_sizes'))
                    ->setKey('widget-total-media-sizes')
                    ->setIcon('ti ti-file')
                    ->setColor('info')
                    ->setStatsTotal(BaseHelper::humanFilesize(MediaFile::query()->sum('size')))
                    ->setRoute(route('media.index'))
                    ->setColumn('col-12 col-md-6 col-lg-3')
                    ->setPriority(800)
                    ->init($widgets, $widgetSettings);
            }, 820, 2);
        });

        return $this;
    }

    protected function configRateLimiterForApi(): void
    {
        RateLimiter::for('cloudify-api', function (Request $request) {
            return Limit::perMinute(
                config('packages.cloudify.cloudify.rate_limit_per_minute', 300)
            )->by($request->user()?->id ?: $request->ip());
        });
    }
}
