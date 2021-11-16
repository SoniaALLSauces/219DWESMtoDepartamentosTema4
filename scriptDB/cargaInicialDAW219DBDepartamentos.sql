/**
 * Author:  Sonia Antón Llanes
 * Created: 3 nov. 2021
 */


    /* Pongo en uso la base de datos creada en script creaDAW2SALDBDepartamentos */
        use DB219DWESProyectoTema4;  

    /* Creo la tabla Departamentos si no existe */
        INSERT INTO Departamento(CodDepartamento,DescDepartamento,FechaBaja,VolumenNegocio) VALUES
            ('INF', 'Departamento de informatica',null,1),
            ('VEN', 'Departamento de ventas',null,2),
            ('CON', 'Departamento de contabilidad',null,3),
            ('MTO', 'Departamento de mantenimiento',null,5),
            ('ADM', 'Departamento de administracion',null,6);
