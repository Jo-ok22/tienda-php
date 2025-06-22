CREATE TABLE orden_detalle (
  id INT AUTO_INCREMENT PRIMARY KEY,
  orden_id INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad INT NOT NULL,
  FOREIGN KEY (orden_id) REFERENCES ordenes(id),
  FOREIGN KEY (producto_id) REFERENCES productos(id)
);