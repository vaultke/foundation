<?php
$item =$generator->getControllerID();
$items=\yii\helpers\Inflector::pluralize($item);
$model = \yii\helpers\StringHelper::basename($generator->modelClass);
echo "<?php\n";
?>
return [
//security={{}} #disable authorization on an endpoint
/**
 * @OA\Get(path="/<?=$items;?>",
 *   summary="Lists all <?= $model ?> models ",
 *   tags={"<?=$model?>"},
 *   @OA\Response(
 *     response=200,
 *     description="Returns a data payload object for all <?=$items?>",
 *      @OA\JsonContent(
 *          @OA\Property(property="dataPayload", type="object",
 *              @OA\Property(property="data", type="array",@OA\Items(ref="#/components/schemas/<?=$model?>")),
 *              @OA\Property(property="countOnPage", type="integer", example="25"),
 *              @OA\Property(property="totalCount", type="integer",example="50"),
 *              @OA\Property(property="perPage", type="integer",example="25"),
 *              @OA\Property(property="totalPages", type="integer",example="2"),
 *              @OA\Property(property="currentPage", type="integer",example="1"),
 *              @OA\Property(property="paginationLinks", type="object",
 *                  @OA\Property(property="self", type="string",example="prefix/context/<?=$items?>?page=1&per-page=25"),
 *                  @OA\Property(property="first", type="string",example="prefix/context/<?=$items?>?page=1&per-page=25"),
 *                  @OA\Property(property="last", type="string",example="prefix/context/<?=$items?>?page=1&per-page=25"),
 *              ),
 *          )
 *      )
 *   ),
 * )
 */
'GET <?=$items?>'         => '<?=$item?>/index',

/**
 * @OA\Post(
 * path="/<?=$item?>",
 * summary="Creates a new <?=$model?> model ",
 * tags={"<?=$model?>"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Fill in <?=$item?> data",
 *    @OA\JsonContent(
 *       required={<?=$generator->generateRequiredRules()?>},
 *       ref="#/components/schemas/<?=$model?>",
 *    ),
 * ),
 * @OA\Response(
 *    response=201,
 *    description="Data payload",
 *    @OA\JsonContent(
 *       @OA\Property(property="dataPayload", type="object",
 *          @OA\Property(property="data", type="object",ref="#/components/schemas/<?=$model?>"),
 *          @OA\Property(property="toastMessage", type="string", example="<?=$item?> created succefully"),
 *          @OA\Property(property="toastTheme", type="string",example="success"),
 *       )
 *    )
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Data Validation Error",
 *    @OA\JsonContent(
 *       @OA\Property(property="errorPayload", type="object",
 *          @OA\Property(property="errors", type="object", ref="#/components/schemas/<?=$model?>"),
 *          @OA\Property(property="toastMessage", type="string", example="Some data could not be validated"),
 *          @OA\Property(property="toastTheme", type="string",example="danger"),
 *       )
 *    )
 * )
 *),
 */
'POST <?=$item?>'         => '<?=$item?>/create',

/**
 * @OA\Get(path="/<?=$item;?>/{id}",
 *   summary="Displays a single <?=$model?> model",
 *   tags={"<?=$model?>"},
 *     @OA\Parameter(
 *         description="<?= $model ?> ID to view",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *   @OA\Response(
 *     response=200,
 *     description="Displays a single <?=$model?> model.",
 *      @OA\JsonContent(
 *          @OA\Property(property="dataPayload", type="object", ref="#/components/schemas/<?=$model?>"))
 *      ),
 *   @OA\Response(
 *     response=404,
 *     description="Resource not found",
 *      @OA\JsonContent(
 *          @OA\Property(property="errorPayload", type="object"))
 *      )
 *   ),
 * )
 */
'GET <?=$item?>/{id}'     => '<?=$item?>/view',

/**
* @OA\Put(
*     path="/<?=$item?>/{id}",
*     tags={"<?=$model?>"},
*     summary="Updates an existing <?=$model?> model",
*     @OA\Parameter(
*         description="<?= $model ?> ID to update",
*         in="path",
*         name="id",
*         required=true,
*         @OA\Schema(
*             type="string",
*         )
*     ),
*     @OA\RequestBody(
*        required=true,
*        description="Finds the <?=$model?> model to be updated based on its primary key value",
*        @OA\JsonContent(
*           ref="#/components/schemas/<?=$model?>",
*        ),
*     ),
*    @OA\Response(
*       response=202,
*       description="Data payload",
*       @OA\JsonContent(
*          @OA\Property(property="dataPayload", type="object",
*             @OA\Property(property="data", type="object",ref="#/components/schemas/<?=$model?>"),
*             @OA\Property(property="toastMessage", type="string", example="<?=$item?> updated succefully"),
*             @OA\Property(property="toastTheme", type="string",example="success"),
*          )
*       )
*    ),
*    @OA\Response(
*         response=404,
*         description="Resource not found",
*         @OA\JsonContent(
*           @OA\Property(property="errorPayload", type="object")
*         )
*     ),
* )
*/
'PUT <?=$item?>/{id}'     => '<?=$item?>/update',

/**
* @OA\Delete(path="/<?=$item?>/{id}",
*    tags={"<?=$model?>"},
*    summary="Deletes an existing <?=$model?> model.",
*    @OA\Parameter( name="id", in="path", required=true,
*     @OA\Schema(
*         type="string"
*     )
*   ),
*     @OA\Response(
*         response=404,
*         description="Resource not found",
*         @OA\JsonContent(
*           @OA\Property(property="errorPayload", type="object")
*         )
*     ),
*     @OA\Response(
*         response=202,
*         description="Deletion successful",
*         @OA\JsonContent(
*           @OA\Property(property="dataPayload", type="object")
*         )
*     ),
* )
*/
'DELETE <?=$item?>/{id}'  => '<?=$item?>/delete',

/**
* @OA\Patch(path="/<?=$item?>/{id}",
*    tags={"<?=$model?>"},
*    summary="Restores a deleted <?=$model?> model.",
*    @OA\Parameter( name="id", in="path", required=true,
*     @OA\Schema(
*         type="string"
*     )
*   ),
*     @OA\Response(
*         response=404,
*         description="Resource not found",
*         @OA\JsonContent(
*           @OA\Property(property="errorPayload", type="object")
*         )
*     ),
*     @OA\Response(
*         response=202,
*         description="Restore successful",
*         @OA\JsonContent(
*           @OA\Property(property="dataPayload", type="object")
*         )
*     ),
* )
*/
'PATCH <?=$item?>/{id}'  => '<?=$item?>/restore',
];


