# Dinen Docker Container
###### Get Dinen, Eat Food 

### 0. Description

#### 1. Requierments
* Download and install Docker (container based app deployment) from [here](https://www.docker.com/community-edition#/download).
* Obtain the Git repo, `git clone https://github.com/thee-engineer/dinen.git dinen`
* Go to the Dinen Git repository on your machine `cd path/to/dinen` and `git pull` the latest version
  * Then switch to the new Docker branch `git checkout docker`
    * Run `git branch` and make sure the output says `docker`

#### 1. Building
* Make sure Docker Daemon is running!
* Inside the `path/to/dinen` repo, there should be a executable called `make`
  * Execute it `./make`, this should create the Dinen Docker Image
    * If any errors occur during compilation, open an Issue
    * If you can't run the script, look inside it and manually tell docker to make it

#### 2. Running
* Make sure Docker Daemon is running!
* Inside the `path/to/dinen` repo, there should be a executable called `run`
  * Execute it `./run`, this should mount the Docker Image inside a container
    * By default it will open port `80` to port `8080` and `443` to `8081`
      * Change this by editing the `run` file and restart the container
    * Also port `3306` to `8082` for the MySQL database

#### 3. Exiting
* In order to exit, type the following command `docker container list`
  * This will list all running container (if any).
  * Each container has a hash assigned to it, like this `dab418b1d322`
  * In order to close a running container write `docker stop dab418b1d322`
    * Use the hash of your machine!
  * Run `docker container list` again to make sure the container is closed

#### 4. Removal
* Stopping the container did not remove the container
* In order to see dormant containers, type `docker container list -a`
* Now you will see all containeres that ever existed
  * Find the container you want to remove (notice it also has a name)
  * In order to delete (yes it is permanent), type `docker rm dab418b1d322`
    * Use the hash of the container you wish to remove
    * TIP: You can sometimes type only the first two chars of the hash
      * `docker rm da`

#### 5. Image removal
* If for any reason you want to remove the Dinen Docker Image
  * Maybe it takes too much of your disk space
* Type `docker images` to list all the docker images you have downloaded/compiled
* Find the image you wish to remove (should be called dinen) and copy the hash
  * Then type `docker rmi a2494f524dd3`
    * Make sure you use the hash of the image YOU wish to remove
* There might/should also be a `ubuntu:latest` image, remove that if you don't need it