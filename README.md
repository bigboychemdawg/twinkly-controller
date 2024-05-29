# twinkly-controller

The reason for creating this controller is an unresolved issue that has persisted for a long time, described here: https://github.com/home-assistant/core/issues/102809. This solution will likely become obsolete after the official fix for the problem.
The controller allows you to manage the state (on/off) and brightness of your Twinkly device.

What we will need:
1. Docker + Docker Compose
2. A static IP address for your Twinkly device

**STEP 0**

Connect your Twinkly device to your network using the official app. In your router's settings, assign a static IP address to the device.

**STEP 1**

Install Docker and Docker Compose on your Home Assistant server. There are numerous guides available online on how to do this, so I won't describe the process here. I will only mention that I used Docker Compose v2, so the commands described here will be without a hyphen (if you are using an older version, the commands are executed using docker-compose).

**STEP 2**

Upload the files `twinkly_api.php`, `apache-dockerfile`, and `apache-docker-compose.yml` to your Home Assistant server in a single directory. Then, execute the following command to build and run the container with the Apache web server, which Home Assistant will use to send requests to your Twinkly device:

`docker-compose -f apache-docker-compose.yml up --build -d`

**STEP 3**

Next, we need to edit the configuration.yaml file in Home Assistant. In my case, Home Assistant also runs in a Docker container, so I will edit it there.

`docker exec -it homeassistant sh`
`nano configuration.yaml`

At the end of your configuration.yaml file, add the contents of the configuration.yaml file from this repository. 

> IMPORTANT: 
> After inserting the content into configuration.yaml, replace all instances of the IP address with the actual IP address of your Twinkly device.

Next, you need to restart Home Assistant.

`docker restart homeassistant`

**STEP 4**

Add a card to your dashboard to control the Twinkly device. Here is how it looks on my dashboard:

```
type: entities
entities:
  - entity: light.twinkly
title: Bedroom
```
