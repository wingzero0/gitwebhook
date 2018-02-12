Symfony Standard Edition
========================

# commands
## init setup

## background services
    php bin/console gearman:worker:execute AppBundleServicesGitWorkerDummy --no-interaction
    
## run build-in server
    php bin/console server:start 0.0.0.0:8000
    
# git repo init
repo must created by 
    git clone REMOTE_URL --mirror