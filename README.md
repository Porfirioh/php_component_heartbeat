# PHP Component Heartbeat

php component heartbeat provides interfaces and abstract implementations to provide a generic code base.

You should use this to implement your real heartbeat monitoring solution.

## Basic Concept

The heartbeat is divided into two components, the heartbeat and the monitor.

The main idea is to provide a heartbeat over parallel running threads or on different locations.

Both components are designed to be used on the monitor (observer) as well as on the heartbeat (client) side.

You should create process that uses the monitor that knocks attached heartbeats. The heartbeat is used in the monitor to *knock* and is used in the process to *beat*. The *beat* is called in the process and updates the status informations. The *knock* reads the status information and provides it to the monitor (if needed). The *knock* also throws an exception. If an exception occures, the monitor has to call the heartbeat method *handleHeartAttack* since the heartbeat itself only knows what to do and how to do.  
The heartbeat can implement a *PulseableInterface*. This can be used to call the heartbeat only every $x seconds. How to handle a call below the pulse can be implemented in the heartbeat. To call the heartbeat in an interval with a minimum of the provided pulse is a gentleman agreement between the monitor and the heartbeat.

### Heartbeat

The heartbeat itself has a *beat* method.
This should be called as often as necessary to update the heartbeat information.
If you iterate over a chunk of items, simple call the *beat* after each or after ten processed items.

Furthermore, the heartbeat knows where to look for the information and how to return the values to the monitor.
This part has to be handled in the *knock* method.

### Heartbeat Monitor

The heartbeat monitor provides attach and detach mechanisms for a heartbeat. The only thing you need to do is, to implement a way to store the attached heartbeats in a persistent way (database, filesystem, session).

### Additional Interfaces

The component also declares a *PulseableInterface* as well as a *RuntimeInformationInterface*. Both can be used to retrieve data from the heartbeat (client).

### Workflow

#### From The Perspective Of The Supervised Process (The Client)

The process would instantiate the heartbeat and the monitor.
The monitor is used to attach the heartbeat (meaning, adding the heartbeat to the list of observable heartbeats).
After that and depending on the heartbeat implementation in the process, the heartbeat would be used to update the status by calling the *beat* method.
If the process is finished, it would use the monitor to detach the heartbeat from the list of observable heartbeats.

#### From The Perspective Of The Monitor (The Observer)

The monitor would take a look to the monitorable heartbeats and tries to knock each heartbeat. If the knock method of the heartbeat returns nothing or throws an exception, the monitor needs to take care. It is recommended to use the heartbeat method *handleHeartAttack*. This method should take care of cleaning up a died process.

## What Do You Need To Do

PHP scripts are executed by a request.
PHP itself is not designed for long running process.
This leads to the fact that you are using the forking or threading mechanism of your webserver.
How to keep track of your threads is your business.

### A Possible Implementation

How to solve the communication between threads?

You have to implement a reachable data transfer object.
For example, you can use a file, a database table or a session storage.

#### Using The Filesystem

A simple idea would be, to use the filesystem.
The heartbeat would create a file with a unique but identifiable name.
The file contains all needed information's.
For example a json file could have the following layout.

    {
        "timestamp": "1373924066",
        "uptime": "1373924000",
        "memoryUsage": "1329232"
    }

From the side of the monitor, the heartbeat would read the file content and return the right one to the monitor.

#### Using The Database

To prevent read-/writelocks or multiple files across the file system you can use a database table that holds the information.
A simple example is following.

    CREATE TABLE `montior_list`
        `id`  INT NOT NULL AUTO_INCREMENT,
        `heartbeat_identity` VARCHAR (255) NOT NULL,
        `timestamp` INT NOT NULL,
        `uptime` INT NOT NULL,
        `memory_usage` INT NOT NULL,
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL,
         PRIMARY KEY (`id`),
         INDEX `createdAt` (`created_at`)
    ) Engine=InnoDB DEFAULT CHARSET=utf8 COMMENT='my short comment';

# Examples

## JSON Based Implementation

    php example/Example/JSONBasedImplementation/Example.php

Investigate code if you want to.
