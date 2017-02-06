#!/bin/bash

REPO_NAME=louis-zend
ACCOUNT_URL=226019795248.dkr.ecr.ap-southeast-2.amazonaws.com
SERVICE_NAME=louis-zend
TASK_FAMILY=louis-zend
CLUSTER_NAME=louis-zend1

function deploy {
    version=${1:-latest}
    echo deploy to ECS with version: $version
    CLUSTER_NAME=$CLUSTER_NAME BUILD_NUMBER=$version SERVICE_NAME=$SERVICE_NAME TASK_FAMILY=$TASK_FAMILY ./update-service.sh
}

function local_dev {
    version=${1:-local_dev}
    echo local_dev on version : $version
    docker rm -f local_dev_skeleton_server
    docker build -t local_skeleton_dev -f $(pwd)/Dockerfile_dev --build-arg PHP_PROJECT_VER=$version . \
    && docker run -tid -p 80:80 --name="local_dev_skeleton_server" -v $(pwd):/var/www local_skeleton_dev
}

function build_push {
    version=${1:-latest}
    echo build and push image to ECS with version: $version
    echo version = $version
    docker build -t $REPO_NAME --build-arg PHP_PROJECT_VER=$version . \
    && docker tag $REPO_NAME:latest $ACCOUNT_URL/$REPO_NAME:$version \
    && docker tag $REPO_NAME:latest $ACCOUNT_URL/$REPO_NAME:latest \
    && docker push $ACCOUNT_URL/$REPO_NAME:$version \
    && docker push $ACCOUNT_URL/$REPO_NAME:latest
}

function unittest {
    echo params : $@
    ./vendor/phpunit/phpunit/phpunit ./tests/
}
function test_script {
    version=${1:-latest}
    echo build and push image to ECS with version: $version
    echo version = $version
}

function help_message {
    echo l for local_dev
    echo pm for build and push image to ECS
    echo d for deploy new image to ECS
    echo a for both pm and d
}

function main {
  	case $1 in
		l) local_dev "${@:2}";;
		p) git pull --rebase && unittest "${@:2}" && git push origin master ;;
		pm) unittest "${@:2}" && build_push "${@:2}" ;;
		d) deploy "${@:2}";;
		a) unittest "${@:2}" && build_push "${@:2}" && deploy "${@:2}";;
		ts) test_script "${@:2}" ;;
		t) unittest "${@:2}" ;;
		*) help_message ;;
	esac
}

main $@
