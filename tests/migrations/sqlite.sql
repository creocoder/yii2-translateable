/**
 * SQLite
 */

DROP TABLE IF EXISTS "post";

CREATE TABLE "post" (
  "id" INTEGER NOT NULL PRIMARY KEY
);

DROP TABLE IF EXISTS "post_translation";

CREATE TABLE "post_translation" (
  "post_id"  INTEGER NOT NULL,
  "language" TEXT    NOT NULL,
  "title"    TEXT    NOT NULL,
  "body"     TEXT    NOT NULL,
  PRIMARY KEY ("post_id", "language")
);
