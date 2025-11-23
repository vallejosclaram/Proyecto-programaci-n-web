<?php

require_once(__DIR__ . '/../../connection.php');

class Permisos
{
    // Verifica si el usuario tiene un permiso puntual
    public static function tienePermiso($permiso, $idUsuario)
    {
        if (!$permiso || !$idUsuario) {
            return false;
        }

        if (!is_array($permiso)) {
            $permisos = [$permiso];
        } else {
            $permisos = $permiso;
        }

        return self::tieneAlgunPermiso($permisos, $idUsuario);
    }

    // Verifica si tiene algún permiso de una lista
    public static function tieneAlgunPermiso($permisos, $idUsuario)
    {
        global $conn;

        if (!$permisos || !is_array($permisos) || !$idUsuario) {
            return false;
        }

        // Construcción dinámica del IN (:permiso0, :permiso1, ...)
        $bindPermisos = implode(',', array_map(function ($p, $k) {
            return ":permiso$k";
        }, $permisos, array_keys($permisos)));

        $sql = "
            SELECT 1
            FROM permisos
            INNER JOIN roles_permisos 
                ON roles_permisos.id_permiso = permisos.id
            INNER JOIN usuario_rol 
                ON usuario_rol.id_rol = roles_permisos.id_rol
            WHERE usuario_rol.id_usuario = :idUsuario
              AND permisos.nombre IN ($bindPermisos)
            LIMIT 1;
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":idUsuario", $idUsuario);

        array_walk($permisos, function ($p, $k) use ($stmt) {
            $stmt->bindValue(":permiso$k", $p);
        });

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return !empty($result);
    }

    // Devuelve todos los permisos del usuario
    public static function getPermisos($idUsuario)
    {
        global $conn;

        $sql = "
            SELECT permisos.nombre
            FROM permisos
            INNER JOIN roles_permisos 
                ON roles_permisos.id_permiso = permisos.id
            INNER JOIN usuario_rol
                ON usuario_rol.id_rol = roles_permisos.id_rol
            WHERE usuario_rol.id_usuario = :idUsuario
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':idUsuario', $idUsuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve todos los roles del usuario
    public static function getRoles($idUsuario)
    {
        global $conn;

        $sql = "
            SELECT r.nombre_rol
            FROM rol r
            INNER JOIN usuario_rol ur
                ON ur.id_rol = r.id_rol
            WHERE ur.id_usuario = :idUsuario
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':idUsuario', $idUsuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verifica si el usuario es de un rol
    public static function esRol($rol, $idUsuario)
    {
        if (!$rol || !$idUsuario) {
            return false;
        }

        if (!is_array($rol)) {
            $roles = [$rol];
        } else {
            $roles = $rol;
        }

        return self::esAlgunRol($roles, $idUsuario);
    }

    // Verifica si el usuario tiene al menos uno de los roles indicados
    public static function esAlgunRol($roles, $idUsuario)
    {
        global $conn;

        if (!$roles || !is_array($roles) || !$idUsuario) {
            return false;
        }

        $bindRoles = implode(',', array_map(function ($r, $k) {
            return ":rol$k";
        }, $roles, array_keys($roles)));

        $sql = "
            SELECT 1
            FROM rol
            INNER JOIN usuario_rol ur
                ON ur.id_rol = rol.id_rol
            WHERE ur.id_usuario = :idUsuario
              AND rol.nombre_rol IN ($bindRoles)
            LIMIT 1;
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':idUsuario', $idUsuario);

        array_walk($roles, function ($r, $k) use ($stmt) {
            $stmt->bindValue(":rol$k", $r);
        });

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return !empty($result);
    }
}
