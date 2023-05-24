CREATE TABLE languages (
    language_code VARCHAR(2) PRIMARY KEY,
    language_name VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO languages (language_code, language_name) VALUES
    ('EN', 'Anglais'),
    ('DE', 'Allemand'),
    ('ES', 'Espagnol'),
    ('FR', 'Français'),
    ('IT', 'Italien'),
    ('PT', 'Portugais');

CREATE TABLE translates (
    table_name VARCHAR(50) NOT NULL,
    row_id INT(11) NOT NULL,
    value VARCHAR(255) NOT NULL,
    language_code VARCHAR(2) NOT NULL,
    FOREIGN KEY (language_code) REFERENCES languages(language_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE symbols (
    symbol_id INT(11) NOT NULL AUTO_INCREMENT,
    file_name VARCHAR(100) NOT NULL,
    size INT(11) DEFAULT 50,
    active TINYINT(1) DEFAULT 0,
    deleted TINYINT(1) DEFAULT 0,
    PRIMARY KEY (symbol_id),
	UNIQUE (`file_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE keywords (
    keyword_id INT(11) NOT NULL AUTO_INCREMENT,
    keyword VARCHAR(100) NOT NULL,
    PRIMARY KEY (keyword_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE categories (
    category_id INT(11) NOT NULL AUTO_INCREMENT,
    category VARCHAR(100) NOT NULL,
    PRIMARY KEY (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE symbol_keyword (
    symbol_id INT(11) NOT NULL,
    keyword_id INT(11) NOT NULL,
    FOREIGN KEY (symbol_id) REFERENCES symbols(symbol_id),
    FOREIGN KEY (keyword_id) REFERENCES keywords(keyword_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE symbol_category (
    symbol_id INT(11) NOT NULL,
    category_id INT(11) NOT NULL,
    FOREIGN KEY (symbol_id) REFERENCES symbols(symbol_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
