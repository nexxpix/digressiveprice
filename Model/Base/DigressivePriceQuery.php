<?php

namespace DigressivePrice\Model\Base;

use \Exception;
use \PDO;
use DigressivePrice\Model\DigressivePrice as ChildDigressivePrice;
use DigressivePrice\Model\DigressivePriceQuery as ChildDigressivePriceQuery;
use DigressivePrice\Model\Map\DigressivePriceTableMap;
use DigressivePrice\Model\Thelia\Model\Product;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'digressive_price' table.
 *
 *
 *
 * @method     ChildDigressivePriceQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildDigressivePriceQuery orderByProductId($order = Criteria::ASC) Order by the product_id column
 * @method     ChildDigressivePriceQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method     ChildDigressivePriceQuery orderByPromoPrice($order = Criteria::ASC) Order by the promo_price column
 * @method     ChildDigressivePriceQuery orderByQuantityFrom($order = Criteria::ASC) Order by the quantity_from column
 * @method     ChildDigressivePriceQuery orderByQuantityTo($order = Criteria::ASC) Order by the quantity_to column
 *
 * @method     ChildDigressivePriceQuery groupById() Group by the id column
 * @method     ChildDigressivePriceQuery groupByProductId() Group by the product_id column
 * @method     ChildDigressivePriceQuery groupByPrice() Group by the price column
 * @method     ChildDigressivePriceQuery groupByPromoPrice() Group by the promo_price column
 * @method     ChildDigressivePriceQuery groupByQuantityFrom() Group by the quantity_from column
 * @method     ChildDigressivePriceQuery groupByQuantityTo() Group by the quantity_to column
 *
 * @method     ChildDigressivePriceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDigressivePriceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDigressivePriceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDigressivePriceQuery leftJoinProduct($relationAlias = null) Adds a LEFT JOIN clause to the query using the Product relation
 * @method     ChildDigressivePriceQuery rightJoinProduct($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Product relation
 * @method     ChildDigressivePriceQuery innerJoinProduct($relationAlias = null) Adds a INNER JOIN clause to the query using the Product relation
 *
 * @method     ChildDigressivePrice findOne(ConnectionInterface $con = null) Return the first ChildDigressivePrice matching the query
 * @method     ChildDigressivePrice findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDigressivePrice matching the query, or a new ChildDigressivePrice object populated from the query conditions when no match is found
 *
 * @method     ChildDigressivePrice findOneById(int $id) Return the first ChildDigressivePrice filtered by the id column
 * @method     ChildDigressivePrice findOneByProductId(int $product_id) Return the first ChildDigressivePrice filtered by the product_id column
 * @method     ChildDigressivePrice findOneByPrice(double $price) Return the first ChildDigressivePrice filtered by the price column
 * @method     ChildDigressivePrice findOneByPromoPrice(double $promo_price) Return the first ChildDigressivePrice filtered by the promo_price column
 * @method     ChildDigressivePrice findOneByQuantityFrom(int $quantity_from) Return the first ChildDigressivePrice filtered by the quantity_from column
 * @method     ChildDigressivePrice findOneByQuantityTo(int $quantity_to) Return the first ChildDigressivePrice filtered by the quantity_to column
 *
 * @method     array findById(int $id) Return ChildDigressivePrice objects filtered by the id column
 * @method     array findByProductId(int $product_id) Return ChildDigressivePrice objects filtered by the product_id column
 * @method     array findByPrice(double $price) Return ChildDigressivePrice objects filtered by the price column
 * @method     array findByPromoPrice(double $promo_price) Return ChildDigressivePrice objects filtered by the promo_price column
 * @method     array findByQuantityFrom(int $quantity_from) Return ChildDigressivePrice objects filtered by the quantity_from column
 * @method     array findByQuantityTo(int $quantity_to) Return ChildDigressivePrice objects filtered by the quantity_to column
 *
 */
abstract class DigressivePriceQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \DigressivePrice\Model\Base\DigressivePriceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\DigressivePrice\\Model\\DigressivePrice', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDigressivePriceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDigressivePriceQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \DigressivePrice\Model\DigressivePriceQuery) {
            return $criteria;
        }
        $query = new \DigressivePrice\Model\DigressivePriceQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDigressivePrice|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DigressivePriceTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DigressivePriceTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildDigressivePrice A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, PRODUCT_ID, PRICE, PROMO_PRICE, QUANTITY_FROM, QUANTITY_TO FROM digressive_price WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildDigressivePrice();
            $obj->hydrate($row);
            DigressivePriceTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildDigressivePrice|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DigressivePriceTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DigressivePriceTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DigressivePriceTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DigressivePriceTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DigressivePriceTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the product_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProductId(1234); // WHERE product_id = 1234
     * $query->filterByProductId(array(12, 34)); // WHERE product_id IN (12, 34)
     * $query->filterByProductId(array('min' => 12)); // WHERE product_id > 12
     * </code>
     *
     * @see       filterByProduct()
     *
     * @param     mixed $productId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function filterByProductId($productId = null, $comparison = null)
    {
        if (is_array($productId)) {
            $useMinMax = false;
            if (isset($productId['min'])) {
                $this->addUsingAlias(DigressivePriceTableMap::PRODUCT_ID, $productId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($productId['max'])) {
                $this->addUsingAlias(DigressivePriceTableMap::PRODUCT_ID, $productId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DigressivePriceTableMap::PRODUCT_ID, $productId, $comparison);
    }

    /**
     * Filter the query on the price column
     *
     * Example usage:
     * <code>
     * $query->filterByPrice(1234); // WHERE price = 1234
     * $query->filterByPrice(array(12, 34)); // WHERE price IN (12, 34)
     * $query->filterByPrice(array('min' => 12)); // WHERE price > 12
     * </code>
     *
     * @param     mixed $price The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(DigressivePriceTableMap::PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(DigressivePriceTableMap::PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DigressivePriceTableMap::PRICE, $price, $comparison);
    }

    /**
     * Filter the query on the promo_price column
     *
     * Example usage:
     * <code>
     * $query->filterByPromoPrice(1234); // WHERE promo_price = 1234
     * $query->filterByPromoPrice(array(12, 34)); // WHERE promo_price IN (12, 34)
     * $query->filterByPromoPrice(array('min' => 12)); // WHERE promo_price > 12
     * </code>
     *
     * @param     mixed $promoPrice The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function filterByPromoPrice($promoPrice = null, $comparison = null)
    {
        if (is_array($promoPrice)) {
            $useMinMax = false;
            if (isset($promoPrice['min'])) {
                $this->addUsingAlias(DigressivePriceTableMap::PROMO_PRICE, $promoPrice['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($promoPrice['max'])) {
                $this->addUsingAlias(DigressivePriceTableMap::PROMO_PRICE, $promoPrice['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DigressivePriceTableMap::PROMO_PRICE, $promoPrice, $comparison);
    }

    /**
     * Filter the query on the quantity_from column
     *
     * Example usage:
     * <code>
     * $query->filterByQuantityFrom(1234); // WHERE quantity_from = 1234
     * $query->filterByQuantityFrom(array(12, 34)); // WHERE quantity_from IN (12, 34)
     * $query->filterByQuantityFrom(array('min' => 12)); // WHERE quantity_from > 12
     * </code>
     *
     * @param     mixed $quantityFrom The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function filterByQuantityFrom($quantityFrom = null, $comparison = null)
    {
        if (is_array($quantityFrom)) {
            $useMinMax = false;
            if (isset($quantityFrom['min'])) {
                $this->addUsingAlias(DigressivePriceTableMap::QUANTITY_FROM, $quantityFrom['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quantityFrom['max'])) {
                $this->addUsingAlias(DigressivePriceTableMap::QUANTITY_FROM, $quantityFrom['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DigressivePriceTableMap::QUANTITY_FROM, $quantityFrom, $comparison);
    }

    /**
     * Filter the query on the quantity_to column
     *
     * Example usage:
     * <code>
     * $query->filterByQuantityTo(1234); // WHERE quantity_to = 1234
     * $query->filterByQuantityTo(array(12, 34)); // WHERE quantity_to IN (12, 34)
     * $query->filterByQuantityTo(array('min' => 12)); // WHERE quantity_to > 12
     * </code>
     *
     * @param     mixed $quantityTo The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function filterByQuantityTo($quantityTo = null, $comparison = null)
    {
        if (is_array($quantityTo)) {
            $useMinMax = false;
            if (isset($quantityTo['min'])) {
                $this->addUsingAlias(DigressivePriceTableMap::QUANTITY_TO, $quantityTo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quantityTo['max'])) {
                $this->addUsingAlias(DigressivePriceTableMap::QUANTITY_TO, $quantityTo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DigressivePriceTableMap::QUANTITY_TO, $quantityTo, $comparison);
    }

    /**
     * Filter the query by a related \DigressivePrice\Model\Thelia\Model\Product object
     *
     * @param \DigressivePrice\Model\Thelia\Model\Product|ObjectCollection $product The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function filterByProduct($product, $comparison = null)
    {
        if ($product instanceof \DigressivePrice\Model\Thelia\Model\Product) {
            return $this
                ->addUsingAlias(DigressivePriceTableMap::PRODUCT_ID, $product->getId(), $comparison);
        } elseif ($product instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DigressivePriceTableMap::PRODUCT_ID, $product->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByProduct() only accepts arguments of type \DigressivePrice\Model\Thelia\Model\Product or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Product relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function joinProduct($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Product');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Product');
        }

        return $this;
    }

    /**
     * Use the Product relation Product object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DigressivePrice\Model\Thelia\Model\ProductQuery A secondary query class using the current class as primary query
     */
    public function useProductQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProduct($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Product', '\DigressivePrice\Model\Thelia\Model\ProductQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDigressivePrice $digressivePrice Object to remove from the list of results
     *
     * @return ChildDigressivePriceQuery The current query, for fluid interface
     */
    public function prune($digressivePrice = null)
    {
        if ($digressivePrice) {
            $this->addUsingAlias(DigressivePriceTableMap::ID, $digressivePrice->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the digressive_price table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DigressivePriceTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DigressivePriceTableMap::clearInstancePool();
            DigressivePriceTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildDigressivePrice or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildDigressivePrice object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DigressivePriceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DigressivePriceTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        DigressivePriceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DigressivePriceTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // DigressivePriceQuery
