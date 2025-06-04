-- Agregar campo teléfono a la tabla usuarios
ALTER TABLE usuarios
ADD COLUMN telefono VARCHAR(20) DEFAULT NULL AFTER email; 