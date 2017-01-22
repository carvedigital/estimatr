set :deploy_to, "/sites/estimatrapp.com"
set :branch, 'master'

server '46.101.92.86', user: 'estimatr', roles: %w{web app db}
