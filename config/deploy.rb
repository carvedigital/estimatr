# config valid only for Capistrano 3.1
# require 'capistrano/ext/multistage'
# lock '3.5.0'

set :stages, ["production"]
set :default_stage, "production"

set :ssh_options, {:forward_agent => true}
set :application, 'estimatr'
set :repo_url, 'git@github.com:carvedigital/estimatr.git'
set :user, "davzie"

# Default value for linked_dirs is []
set :linked_dirs, %w(storage/app storage/framework/cache storage/framework/sessions storage/framework/views storage/logs)


namespace :deploy do

  desc 'Install Composer Dependancies'
  task :composer_install do
    on roles(:app), in: :groups, limit:1 do
      execute "cd #{release_path} && composer install --no-dev --no-scripts"
    end
  end

  desc 'Remove The View Cache'
  task :remove_view_cache do
    on roles(:app), in: :groups, limit:1 do
      execute "find #{release_path}/storage/framework/views -type f -not -name '.*' -exec rm {} \\;"
    end
  end

  desc 'Run The Migrations'
  task :run_migrations do
    on roles(:app), in: :groups, limit:1 do
      execute "cd #{release_path} && php artisan migrate --force"
    end
  end

  desc 'Setup The Env File'
  task :setup_env do
    on roles(:app), in: :groups, limit:1 do
      execute "ln -s /sites/estimatrapp.com/.env #{ release_path }/.env"
    end
  end

end

after "deploy:updated", "deploy:composer_install"
after "deploy", "deploy:setup_env"
after "deploy", "deploy:remove_view_cache"
after "deploy", "deploy:run_migrations"
