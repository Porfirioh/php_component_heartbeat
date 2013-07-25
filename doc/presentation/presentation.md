# [Heartbeat Component For PHP](https://github.com/stevleibelt/php_component_heartbeat)

## Introduction

* Main Idea
* Preliminary Consideration
* Common Terms
* The Interfaces
* Basic Concept
* Examples
* Whats Left / Room Of Improvment
* Licence, Download And Install

---
# Main Idea

* Component that provides generic interfaces to implement heartbeat monitoring solution
* One observer can handle multiple clients
* Observer can decide how to store or handle the knowledge about clients to observe
* Clients can decide how to handle the data for the monitoring
* Clients are the ones to ask for if something goes wrong
* No dependencies to existing frameworks or systems
* Does not decide how to transfer data between observer and client
* Should be extendable (add more informations to the monitoring when needed)

---

# Preliminary Consideration

## Is Anybody Out There?

I searched for ["monitoring php"](https://github.com/search?l=PHP&q=monitoring&ref=searchresults&type=Repositories) and ["heartbeat php"](https://github.com/search?l=PHP&q=heartbeat&ref=searchresults&type=Repositories) and the simple answer is, no (or i didn't found it).

## What Is Missing?

* Existing components are strong coupled to existing frameworks -> you can't monitor everything
* Only "add ons" to existing code (wordpress plug in)
* Existing components are strong coupled to operation systems or distributions
* Implementation with [frontend stuff](https://github.com/francoism90/monitoring-made-simple) (no mvc layer so all mixed up in some files)
* No support for parallel running clients or clients on different server
* Only monitoring a special kind of clients (like [a/b testing](https://github.com/web-design-weekly/campaign-monitor-dashboard) or [heating control](https://github.com/ekuiter/uvr2web)), not easy to extend
* Only fixed monitoring intervals 
* No php client available
* Started projects only have a initial commit :-)

## Is Cooler Stuff Out There?

Yes, but not as a php component [shinken](http://en.wikipedia.org/wiki/Shinken_(software).

---

# Common Terms

## General

* All terms are take from the scope of an heartattack
* Clients can have a heartattack (something really bad happens, process died)
* Clients can have a arrythmia (something bad happens but process is still running)
* [Client / Observer pattern](http://en.wikipedia.org/wiki/Observer_pattern) used
* Observer is implemented as HeartbeatMonitor
* Client is implemented as Heartbeat

---

# Common Terms

## HeartbeatMonitor

* Acts as observer
* Clients can be attached or detached (by *attach* or *detach*)
* Retrieves client informations and handles them (by *listen*)
* Can collect runtime informations (if client supports) 

## Heartbeat

* Acts as client
* Generates runtime information on its one (by a *beat*)
* Delivers runtime information to monitor (by a *knock*)
* Can handle problems like shutdown or cleanup (by *handleHeartProblems*)

---

# The Interfaces

## [HeartbeatMonitorInterface](https://github.com/stevleibelt/php_component_heartbeat/blob/master/source/Net/Bazzline/Component/Heartbeat/HeartbeatMonitorInterface.php)

    !php
    interface HeartbeatMonitorInterface
    {
        /**
         * Adds a client to the observer
         */
        public function attach(HeartbeatInterface $heartbeat);

        /**
         * Removes an attached client to the observer
         */
        public function detach(HeartbeatInterface $heartbeat);

        /**
         * Returns all attached clients
         */
        public function getAll();

        /**
         * Removes all attached clients
         */
        public function detachAll();

        /**
         * Calls knock for each attached client
         */
        public function listen();
    }

---

# The Interfaces

## [HeartbeatInterface](https://github.com/stevleibelt/php_component_heartbeat/blob/master/source/Net/Bazzline/Component/Heartbeat/HeartbeatInterface.php)

    !php
    interface HeartbeatInterface
    {
        /**
         * This method returns the current timestamp as heartbeat.
         */
        public function knock();

        /**
         * This method updates the current heartbeat.
         */
        public function beat();

        /**
         * Handles case if knock throws an error
         */
        public function handleHeartProblems(RuntimeException $exception);
    }

---

# The Interfaces

## [PulseableInterface](https://github.com/stevleibelt/php_component_heartbeat/blob/master/source/Net/Bazzline/Component/Heartbeat/PulseableInterface.php)

    !php
    interface PulseableInterface
    {
        /**
         * Returns pulse
         */
        public function getPulse();

        /**
         * Sets wished pulse
         */
        public function setPulse($seconds);
    }

---

# The Interfaces

## [RuntimeInformationInterface](https://github.com/stevleibelt/php_component_heartbeat/blob/master/source/Net/Bazzline/Component/Heartbeat/RuntimeInformationInterface.php)

    !php
    interface RuntimeInformationInterface
    {
        /**
         * Returns uptime of the current client in seconds.
         */
        public function getUptime();

        /**
         * Returns memory usage of the current client.
         */
        public function getMemoryUsage();
    }

---

# Basic Concept

* A process is using a heartbeat
* Two instances are running for the same heartbeat, one on the process side and one on the monitor side
* While the process is iterating over data to process, he is calling *heartbeat->beat()* to update runtime information
* When the monitor runs, all available heartbeats where attached and *monitor->listen()* should be called
* The monitor is iterating over the attached heartbeats and is calling *heartbeat->knock()* to get back runtime informations
* The *knock* method can throw an exception. The exceptionhandling is provided by *heartbeat->handleHeartProblems()*
* A *PulsableInterface* can be implemented to the heartbeat. It acts as a gentleman agreement between observer and client to only call *knock* after X seconds

---

# Examples

## [JSONBasedImplementation](https://github.com/stevleibelt/php_component_heartbeat/blob/master/example/Example/JSONBasedImplementation/Example.php)

* One process handles the monitor and the clients
* Simple example with focus on "how to implement data exchange"
* Monitor has a **monitor.json** (for handling available heartbeats)
* Heartbeats have their own **process.json** (for updating status informations)
* Example has a predifend number of process with heart attack and arrythmia

---

# Examples

## [JSONAndFork](https://github.com/stevleibelt/php_component_heartbeat/blob/master/example/Example/JSONAndFork/Example.php)

* Multiple process (real php process!), one for each client
* Proof of concept that component can handle parallel threads
* Can throw json errors because of file read/write race conditions!
* Example has a predifend number of process with heart attack and arrythmia

---

# Whats Left / Room Of Improvment

* No version 1.0.0 bevor this presentation (nevertheless, the interfaces are quite stable)
* What do you think / Any bad vibes?
* Should this component ship with *PulseableInterface* and *RuntimeInformationInterface*
* Ideas for for examples?
* Want to take a look to the code?

---

# Licence, Download And Install

## Licence

Component comes with a [LPGLv3](http://www.gnu.org/copyleft/lesser.html) licence.  

### Quick Summary

    You may copy, distribute and modify the software provided 
    that modifications are open source. However, software that 
    includes the license may release under a different license.
    
[source](http://www.tldrlegal.com/license/gnu-lesser-general-public-license-v3-(lgpl-3.0))

---

# Licence, Download And Install

## Licence

### Why?

* Commercial use is allowed
* Can be used with components of other licences
* Can modify code
* Is shipped without warranty
* No user can bring a legal claim against other users
* Free as in freedom

---

# Licence, Download And Install

## Download And Install

### Github

    git clone https://github.com/stevleibelt/php_component_heartbeat .

### Packagist.org

    require: "net_bazzline/component_heartbeat": "dev-master"

---

# Thanks

You for listening.

Free and open source software and knowledge base out there for having a look what a heartbeat monitoring component needs to have :-).
