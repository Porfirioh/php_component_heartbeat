# PHP Component Heartbeat

php component heartbeat provides interfaces and abstract implementations to provide a generic code base.
You should use this to implement your real heartbeat monitoring solution.

## Basic Concept

The heartbeat is divided into two components, the heartbeat and the monitor.

### Heartbeat

The heartbeat itself delivers the information to the monitor. Furthermore, the heartbeat know where to look for the information and how to return the values to the monitor.

### Heartbeat Monitor

The heartbeat monitor provides attach and detach mechanism for each heartbeat. By enhancing this methods, you can simple implement a way to list existing heartbeats by storing the attached heartbeats to a request data transfer object.

### Workflow

#### From The Perspective Of The Supervised Process

The process would instantiate the heartbeat and the monitor. The monitor is used to attach the heartbeat (meaning, adding the heartbeat to the list of monitorable heartbeats). After that and depending on the heartbeat implementation in the process, the heartbeat would be used to update the status. If the process is finished, it would use the monitor to detach the heatbeat from the list of monitorable heartbeats.

#### From The Perspective Of The Monitor

The monitor would take a look to the monitorable heartbeats and tries to knock each heartbeat. If implemented, it updates or stores additional informations like memory usage or uptime in a list. If the knock method of the heartbeat returns nothing or throws an exception, the monitor needs to take care. It is recommended to implement a method to handle a heartattack.

## What Do You Need To Do

PHP scripts are executed by a request. PHP itself is not designed for long running process. This leads to the fact, that you are using the forking or threading mechanism of your webserver to. How to keep track of your threads is your business.

### A Possible Implementation

How to solve the communication between threads?

You have to implement a reachable data transfer object. You can use a file, a database table or whats out there in the world.

#### Using The Filesystem

A simple idea would be, to use the filesystem. The hearbeat would create a file with a unique but identifiable name. The file contains all needed information's. For example a json file could have the following layout.

    {
        "timestamp": "1373924066",
        "uptime": "1373924000",
        "memoryUsage": "1329232"
    }

From the side of the monitor, the heartbeat would read the file content and return the right one to the monitor.

#### Using The Database

To prevent read-/writelocks or multiple files across the file system, you can use a database table that holds the information. A simple example is following.

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

