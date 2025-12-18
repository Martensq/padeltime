-- Doctrine Migration File Generated on 2025-12-16 09:02:54

-- Version DoctrineMigrations\Version20241008092123
ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEE3184009 FOREIGN KEY (court_id) REFERENCES court (id);
CREATE INDEX IDX_E00CEDDEE3184009 ON booking (court_id);
-- Version DoctrineMigrations\Version20241008092123 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20241008092123', '2025-12-16 09:02:54', 0);
