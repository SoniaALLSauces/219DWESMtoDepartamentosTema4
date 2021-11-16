/**
 * Author:  Sonia Antón Llanes
 * Created: 3 nov. 2021
 * Last Modify: 12 nov. 2021
 */


    /* Creo la base de datos DAW2xxxDBDepartamentos */
        create database if not exists DB219DWESProyectoTema4;

    /* Creo el usuario */
--         create user if not exists 'user219DWESProyectoTema4'@'%' identified by 'paso';
        create user if not exists 'user219DWESProyectoTema4'@'%' identified by 'P@ssw0rd';  /*usuario en casa*/

    /* Damos permisos al usuarioDAW219DBDepartamentos */
        grant all privileges on DB219DWESProyectoTema4.* to 'user219DWESProyectoTema4'@'%';

    /* Pongo en uso la base de datos creada en script creaDAW2SALDBDepartamentos */
        use DB219DWESProyectoTema4;  

    /* Creo la tabla Departamentos si no existe */
        create table if not exists Departamento(  
            codDepartamento varchar(3) primary key not null,
            descDepartamento varchar(255),
            fechaBaja date,
            volumenNegocio float) engine=innodb;