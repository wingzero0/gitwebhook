Symfony Standard Edition
========================

# commands
## init setup

run with root

    HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)
    setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
    setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
    
## background services
    php bin/console gearman:worker:execute AppBundleServicesGitWorkerDummy --no-interaction
    
## run build-in server
    php bin/console server:start 0.0.0.0:8000
    
# git repo init
repo must created by 
    git clone REMOTE_URL --mirror