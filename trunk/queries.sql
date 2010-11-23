SELECT a.mediaId, a.facetObjectId, o.title, o.facetTypeId
FROM jos_facetedvideos_mediafacetassoc a
INNER JOIN jos_facetedvideos_facetobjects o ON a.facetObjectId = o.id
WHERE 1=1
AND (a.mediaId = 37
OR a.mediaId = 38)
ORDER BY a.mediaId ASC, o.title ASC


SELECT v.id, v.title, v.lenght, v.added, v.hits
FROM jos_facetedvideos_mediaobjects v
INNER JOIN jos_facetedvideos_mediafacetassoc a ON a.mediaId = v.id
INNER JOIN jos_facetedvideos_facetobjects o ON a.facetObjectId = o.id
INNER JOIN jos_facetedvideos_facettypes ft ON o.facetTypeId = ft.id
WHERE v.published = 1 AND o.published = 1 AND ft.published = 1
AND (a.facetObjectId = 91
OR a.facetObjectId = 89)
GROUP BY v.id
HAVING SUM(1) = 2;

SELECT v.id
FROM jos_facetedvideos_mediaobjects v
INNER JOIN jos_facetedvideos_mediafacetassoc a ON a.mediaId = v.id
WHERE 1 = 1
AND ( a.facetObjectId = 91
OR a.facetObjectId = 89)
GROUP BY v.id
HAVING SUM(1) = 2;

SELECT Sum(1), a.mediaId FROM jos_facetedvideos_mediafacetassoc a
WHERE 1 = 1
AND (a.facetObjectId = 91
OR a.facetObjectId = 89)
GROUP BY mediaId;

SELECT * FROM jos_facetedvideos_mediafacetassoc a
WHERE 1 = 1
AND (a.facetObjectId = 91
OR a.facetObjectId = 89)



SELECT b.id, a.title as typetitle, b.title, b.published
FROM jos_facetedvideos_facetobjects b
INNER JOIN jos_facetedvideos_facettypes a
  ON a.id = b.facetTypeId
ORDER BY a.title ASC, b.title ASC;

SELECT id
FROM jos_facetedvideos_mediafacetassoc
WHERE id NOT IN (
SELECT a.id
FROM jos_facetedvideos_mediafacetassoc a
INNER JOIN jos_facetedvideos_facetobjects b ON b.id = a.facetObjectId
);